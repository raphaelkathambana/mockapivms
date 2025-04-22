import './style.css'
import axios from 'axios'

// Configure API base URL for Laravel backend
const API_BASE_URL = 'http://127.0.0.1:8000'

// Initialize the result display
let currentResult = null

// Function to update the result display
function updateResult(data) {
  currentResult = data
  const resultContainer = document.querySelector('#result-display')
  resultContainer.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`
}

// Function to create an API request button
function createApiButton(text, endpoint, method = 'GET', body = null) {
  const button = document.createElement('button')
  button.textContent = text
  button.addEventListener('click', async () => {
    try {
      const config = {
        method,
        url: `${API_BASE_URL}${endpoint}`,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'  // Added for Laravel API
        }
      }
      
      if (body) {
        config.data = body
      }
      
      const response = await axios(config)
      updateResult(response.data)
    } catch (error) {
      updateResult({
        error: error.message,
        details: error.response?.data
      })
    }
  })
  return button
}

// Setup the main UI
document.querySelector('#app').innerHTML = `
  <div class="container">
    <div class="sidebar">
      <h2>Laravel API Actions</h2>
      <div class="button-group" id="button-container">
        <!-- Buttons will be added here -->
      </div>
    </div>
    <div class="main-content">
      <h2>Result</h2>
      <div class="result-container">
        <div id="result-display">
          <pre>No request made yet</pre>
        </div>
      </div>
    </div>
  </div>
`

// Add your API endpoint buttons here
const buttonContainer = document.querySelector('#button-container')

// Buttons matching your Laravel routes
buttonContainer.appendChild(createApiButton('Home', '/'))
buttonContainer.appendChild(createApiButton('Get All Items', '/items'))
buttonContainer.appendChild(createApiButton('Create Item Form', '/items/create'))
buttonContainer.appendChild(createApiButton('Create New Item', '/items', 'POST', {
  name: 'New Item',
  description: 'Item Description'
}))