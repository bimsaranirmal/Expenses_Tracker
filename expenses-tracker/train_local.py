import pandas as pd
import numpy as np
from sklearn.linear_model import LinearRegression
import joblib

# Generate dummy data
np.random.seed(42)
n_samples = 1000
X = np.random.normal(loc=50000, scale=10000, size=(n_samples, 6))
y = np.mean(X, axis=1) + np.random.normal(loc=0, scale=2000, size=n_samples)

# Train
model = LinearRegression()
model.fit(X, y)

# Save
joblib.dump(model, 'python/expense_model.pkl')
print("Model trained and saved to python/expense_model.pkl")
