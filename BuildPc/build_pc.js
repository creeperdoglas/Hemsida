document.addEventListener('DOMContentLoaded', () => {
  const componentTypes = [
    'motherboard',
    'cpu',
    'gpu',
    'psu',
    'chassis',
    'fans',
    'storage',
    'ram',
    'cpu_cooler'
  ];

  const productInfoDiv = document.getElementById('product-info');
  const filtersForm = document.getElementById('filters-form');
  const applyFiltersButton = document.getElementById('apply-filters');

  const fetchComponents = (filters = {}) => {
    componentTypes.forEach(type => {
      let query = `build_pc.php?component_type=${type}`;
      Object.keys(filters).forEach(key => {
        if (filters[key]) {
          query += `&${key}=${filters[key]}`;
        }
      });
      fetch(query)
        .then(response => response.json())
        .then(components => {
          console.log(`Fetched components for ${type}:`, components); // Log fetched data
          const select = document.getElementById(type);
          select.innerHTML = ''; // Clear existing options
          if (components.length === 0) {
            const option = document.createElement('option');
            option.value = '';
            option.text = 'No components available';
            select.appendChild(option);
          } else {
            components.forEach(component => {
              const option = document.createElement('option');
              option.value = component.id;
              option.text = component.model;
              option.setAttribute('data-info', JSON.stringify(component));
              select.appendChild(option);

              option.addEventListener('mouseover', (event) => {
                console.log('Hovering over:', event.target); // Debugging hover event
                const info = JSON.parse(event.target.getAttribute('data-info'));
                productInfoDiv.innerHTML = `
                                    <strong>Model:</strong> ${info.model}<br>
                                    <strong>Price:</strong> ${info.price}<br>
                                    <strong>Place:</strong> ${info.place}<br>
                                    <strong>Socket Type:</strong> ${info.socket_type || 'N/A'}<br>
                                    <strong>VRAM:</strong> ${info.vram || 'N/A'}<br>
                                    <strong>Cores:</strong> ${info.cores || 'N/A'}<br>
                                    <strong>Threads:</strong> ${info.threads || 'N/A'}<br>
                                    <strong>Base Clock:</strong> ${info.base_clock || 'N/A'}<br>
                                    <strong>Boost Clock:</strong> ${info.boost_clock || 'N/A'}<br>
                                    <strong>Wattage:</strong> ${info.wattage || 'N/A'}<br>
                                    <strong>Efficiency Rating:</strong> ${info.efficiency_rating || 'N/A'}<br>
                                    <strong>Form Factor:</strong> ${info.form_factor || 'N/A'}<br>
                                    <strong>Size:</strong> ${info.size || 'N/A'}<br>
                                    <strong>RPM:</strong> ${info.rpm || 'N/A'}<br>
                                    <strong>Type:</strong> ${info.type || 'N/A'}<br>
                                    <strong>Capacity:</strong> ${info.capacity || 'N/A'}<br>
                                    <strong>Speed:</strong> ${info.speed || 'N/A'}
                                `;
                productInfoDiv.style.display = 'block';
              });

              option.addEventListener('mouseout', () => {
                productInfoDiv.style.display = 'none';
              });
            });
          }
        })
        .catch(error => console.error('Error fetching components:', error));
    });
  };

  applyFiltersButton.addEventListener('click', () => {
    const filters = {
      brand: document.getElementById('brand-filter').value,
      socket: document.getElementById('socket-filter').value,
      power: document.getElementById('power-filter').value,
      tower: document.getElementById('tower-filter').value
    };
    fetchComponents(filters);
  });

  // Initial fetch without filters
  fetchComponents();
});
