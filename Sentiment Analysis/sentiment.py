# Importing the libraries
import numpy as np
import pandas as pd
import requests
import re
import nltk
import json
from nltk.corpus import stopwords
from nltk.stem.porter import PorterStemmer
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB
from flask import Flask
from flask import request
from flask_cors import CORS, cross_origin

## Created by Saif Lakhani on Cinco de Mayo (5th of May, 2020)
## With Lots of love for Aditi

corpus = []
app = Flask(__name__)
cors = CORS(app, resources={r"/*": {"origins": "*"}})

def get_text_from_json_api(rest_name):
    resp = requests.get('http://localhost/ZestApp/api.php') ## TODO CHANGE THIS TO POINT AT AWS WEBSITE
    if resp.status_code != 200:
        # This means something went wrong.
        print("Something went wrong, check internet or api")
        return 0
    response = resp.json()
    for restaurant in response['data']:
        if restaurant['rest_name'].lower() == rest_name.lower():
            return restaurant['review']

def preprocessing(reviewText):
    global corpus
    if len(reviewText) == 0:
        for i in range(0, 1000):
            review = re.sub('[^a-zA-Z]', ' ', dataset['Review'][i])
            review = review.lower()
            review = review.split()
            ps = PorterStemmer()
            review = [ps.stem(word) for word in review if not word in set(stopwords.words('english'))]
            review = ' '.join(review)
            corpus.append(review)
    else:
        review = re.sub('[^a-zA-Z]', ' ', reviewText)
        review = review.lower()
        review = review.split()
        ps = PorterStemmer()
        review = [ps.stem(word) for word in review if not word in set(stopwords.words('english'))]
        review = ' '.join(review)
        corpus.append(review)
        return review




# Importing the dataset
print("Reading Dataset...")
dataset = pd.read_csv('Restaurant_Reviews.tsv', delimiter = '\t', quoting = 3)
# Preprocess the dataset
print("Preprocessing...")
preprocessing('')

# vectorize
print("Vectorizing...")
cv = CountVectorizer(max_features = 1500)
X = cv.fit_transform(corpus).toarray()
y = dataset.iloc[:, 1].values

# Train
print("Training Multinomial Naive Bayes...")
classifier = MultinomialNB(alpha=0.1)
classifier.fit(X, y)


# Flask API
@app.route("/get_sentiment")
@cross_origin()
def get_sentiment():
    rest_name = request.args['rest_name']
    read_in = get_text_from_json_api(rest_name)
    processed = []
    print("Review ---> ",read_in)
    processed.append(preprocessing(read_in))
    processed = []
    X_Test = cv.fit_transform(corpus).toarray()
    processed.append(X_Test[len(corpus)-1])
    y_pred = classifier.predict(processed)
    if y_pred[0]==1:
        print("Predicted Review is Positive")
        return json.dumps('{"result":"Predicted Review is Positive"}')
    else:
        print("Predicted Review is Negative")
        return json.dumps('{"result":"Predicted Review is Negative"}')

if __name__ == "__main__":
    app.run('0.0.0.0',port=50910)

## TODO Substituted for flask implementation
### MAIN LOOP ###
# rest_name = None
# while rest_name != 'exit':
#     print("\n\nPlease enter restaurant name (exact)")
#     rest_name = input()
#     read_in = get_text_from_json_api(rest_name)
#     processed = []
#     print("Review ---> ",read_in)
#     processed.append(preprocessing(read_in))
#     processed = []
#     X_Test = cv.fit_transform(corpus).toarray()
#     processed.append(X_Test[len(corpus)-1])
#     y_pred = classifier.predict(processed)
#     if y_pred[0]==1:
#         print("Predicted Review is Positive")
#     else:
#         print("Predicted Review is Negative")

