import requests
from bs4 import BeautifulSoup
import mysql.connector
#using temp user atm since i rather not show my password on git, will change so it reads from file later when i have decided which system to run on.
db_config = {
    'host': 'localhost',
    'user': 'temp',
    'password': '12345',
    'database': 'pc_designer'
}
#work in progress
def scrape_cpu_products(url):
    response = requests.get(url)
    if response.status_code != 200:
        return []

    soup = BeautifulSoup(response.text, "html.parser")
    product_cards = soup.find_all("div", class_="c-product-card")

    cpu_data = []
    for card in product_cards:
        # 1) Hämta produktnamn (model)
        #    Titta efter t.ex. "c-product-card__name", "a" länk med produktnamn eller liknande
        name_tag = card.find("h2", class_="c-product-card__name")
        if not name_tag:
            # Om vi inte hittar namnet, hoppa över
            continue
        model = name_tag.get_text(strip=True)

        # 2) Hämta brand, socket, cores, threads, base_clock, boost_clock, tas direkt under info på prisjakt
        brand = "AMD"            # Ex: parse brand ur text: "AMD Ryzen 7..."
        socket_type = "AM5"      # Ex: parse "Socket AM5"
        cores = 8
        threads = 16
        base_clock = 4.0
        boost_clock = 4.7

        # 3) Hämta pris
        #    Ofta nån <span> med klass "c-product-card__price" eller liknande.
        price_tag = card.find("span", class_="c-product-card__price") 
        # Om du inte hittar exakt den klassen, kolla hur priset är markerat i sidans HTML
        if price_tag:
            # Ex: "3 299 kr"
            price_text = price_tag.get_text(strip=True)
        else:
            price_text = "0"

        # Rensa " kr", mellanslag mm:
        # Ex: "3 299 kr" -> "3299" -> 3299.0
        clean_text = (price_text
                      .replace("kr", "")
                      .replace("\xa0", "")
                      .replace(" ", "")
                      .replace(",", "."))
        try:
            price = float(clean_text)
        except ValueError:
            price = 0.0

        # Bygg ett dictionary med all data
        cpu_data.append({
            'model': model,
            'brand': brand,
            'socket_type': socket_type,
            'cores': cores,
            'threads': threads,
            'base_clock': base_clock,
            'boost_clock': boost_clock,
            'price': price
        })

    return cpu_data

def save_cpus_to_db(cpu_list):
 conn = mysql.connector.connect(**db_config)
 cursor = conn.cursor()
 
# SQL för att lägga in en CPU (obs: brand-kolumn finns nu)
 insert_cpu_query = """
        INSERT INTO cpu 
        (model, brand, socket_type, cores, threads, base_clock, boost_clock)
        VALUES (%s, %s, %s, %s, %s, %s, %s)
    """

# SQL för att lägga in pris + store-koppling i cpu_store
 insert_cpu_store_query = """
        INSERT INTO cpu_store (cpu_id, store_id, price)
        VALUES (%s, %s, %s)
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

        new_cpu_id = cursor.lastrowid  # få ID på nyss insatt rad

        # Lägg in i `cpu_store`
        cursor.execute(insert_cpu_store_query, (new_cpu_id, store_id, cpu['price']))

   
 conn.commit()
 cursor.close()
 conn.close()


if __name__ == "__main__":
    url = "https://www.prisjakt.nu/c/processorer"
    data = scrape_cpu_products(url)
    save_cpus_to_db(data)
    print("Scraping klart!")