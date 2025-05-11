#!/bin/bash
export FLASK_APP=app.main:app  # Specify the app instance explicitly
export FLASK_ENV=development
export FLASK_RUN_HOST=0.0.0.0
export FLASK_RUN_PORT=5500
gunicorn -w 4 -b 0.0.0.0:5500 app.main:app  # Use Gunicorn to serve the app