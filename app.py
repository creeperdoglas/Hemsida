from flask import Flask, render_template, jsonify, request
import mysql.connector

app = Flask(__name__)

# Database connection
def get_db_connection():
    conn = mysql.connector.connect(
        host='localhost',
        user='yourusername',
        password='yourpassword',
        database='yourdatabase'
    )
    return conn

@app.route('/build_pc')
def build_pc():
    return render_template('build_pc.html')

@app.route('/get_components/<component_type>')
def get_components(component_type):
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    query = f'SELECT * FROM {component_type}'
    cursor.execute(query)
    components = cursor.fetchall()
    conn.close()
    return jsonify(components)

if __name__ == '__main__':
    app.run(debug=True)
