import requests
from bs4 import BeautifulSoup
import mysql.connector
db_config = {
    'host': 'localhost',
    'user': 'trefrag',
    'password': '',
    'database': 'min_databas'
}
#work in progress
def scrape_cpu_products(url):
    response = requests.get(url)
    if response.status_code != 200:
        return []

    soup = BeautifulSoup(response.text, "html.parser")
    product_elements = soup.find_all("div", class_="product-row")

    cpu_data = []
    for p in product_elements:
      model = p.find("div", class_="product-title").get_text(strip=True)
      price_text = p.find("div", class_="product-price").get_text(strip=True)


      cpu_data.append({
        'model': model,
        'price': ,  
        'place': ,
        'socket_type': ,
        'cores': ,
        'threads': ,
        'base_clock': ,
        'boost_clock': 

      }) 
    return cpu_data

def save_cpus_to_db(cpu_list):
 conn = mysql.connector.connect(**db_config)
 cursor = conn.cursor()
 
 insert_query = """
        INSERT INTO cpu (model, price, place, socket_type, cores, threads, base_clock, boost_clock)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
    """

 for cpu in cpu_list:
        cursor.execute(insert_query, (
            cpu['model'],
            cpu['price'],
            cpu['place'],
            cpu['socket_type'],
            cpu['cores'],
            cpu['threads'],
            cpu['base_clock'],
            cpu['boost_clock']
        ))

 conn.commit()
 cursor.close()
 conn.close()


if __name__ == "__main__":
    url = "https://www.prisjakt.nu/kategori.php?k=123"  # Exempel
    data = scrape_cpu_products(url)
    save_cpus_to_db(data)
    print("Scraping klart!")