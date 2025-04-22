/**
 * API Client for making AJAX requests
 */
class ApiClient {
  /**
   * Make a GET request to the API
   *
   * @param {string} url - The URL to request
   * @param {Object} params - Query parameters
   * @returns {Promise} - Promise resolving to the response data
   */
  static async get(url, params = {}) {
    try {
      // Add query parameters if provided
      const queryString = Object.keys(params).length ? "?" + new URLSearchParams(params).toString() : ""

      const response = await fetch(`${url}${queryString}`, {
        method: "GET",
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
      })

      // Check if response is JSON
      const contentType = response.headers.get("content-type")
      if (!contentType || !contentType.includes("application/json")) {
        console.error("Non-JSON response received:", await response.text())
        throw new Error("The server returned an invalid response. Expected JSON but received HTML.")
      }

      if (!response.ok) {
        const errorData = await response.json()
        throw new Error(errorData.message || "API request failed")
      }

      return await response.json()
    } catch (error) {
      console.error("API GET request failed:", error)
      throw error
    }
  }

  /**
   * Make a POST request to the API
   *
   * @param {string} url - The URL to request
   * @param {Object} data - The data to send
   * @returns {Promise} - Promise resolving to the response data
   */
  static async post(url, data = {}) {
    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content")

      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN": csrfToken,
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
        body: JSON.stringify(data),
      })

      // Check if response is JSON
      const contentType = response.headers.get("content-type")
      if (!contentType || !contentType.includes("application/json")) {
        console.error("Non-JSON response received:", await response.text())
        throw new Error("The server returned an invalid response. Expected JSON but received HTML.")
      }

      if (!response.ok) {
        const errorData = await response.json()
        throw new Error(errorData.message || "API request failed")
      }

      return await response.json()
    } catch (error) {
      console.error("API POST request failed:", error)
      throw error
    }
  }

  /**
   * Make a PUT request to the API
   *
   * @param {string} url - The URL to request
   * @param {Object} data - The data to send
   * @returns {Promise} - Promise resolving to the response data
   */
  static async put(url, data = {}) {
    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content")

      const response = await fetch(url, {
        method: "PUT",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN": csrfToken,
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
        body: JSON.stringify(data),
      })

      // Check if response is JSON
      const contentType = response.headers.get("content-type")
      if (!contentType || !contentType.includes("application/json")) {
        console.error("Non-JSON response received:", await response.text())
        throw new Error("The server returned an invalid response. Expected JSON but received HTML.")
      }

      if (!response.ok) {
        const errorData = await response.json()
        throw new Error(errorData.message || "API request failed")
      }

      return await response.json()
    } catch (error) {
      console.error("API PUT request failed:", error)
      throw error
    }
  }

  /**
   * Make a DELETE request to the API
   *
   * @param {string} url - The URL to request
   * @returns {Promise} - Promise resolving to the response data
   */
  static async delete(url) {
    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content")

      const response = await fetch(url, {
        method: "DELETE",
        headers: {
          Accept: "application/json",
          "X-CSRF-TOKEN": csrfToken,
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
      })

      // Check if response is JSON
      const contentType = response.headers.get("content-type")
      if (!contentType || !contentType.includes("application/json")) {
        console.error("Non-JSON response received:", await response.text())
        throw new Error("The server returned an invalid response. Expected JSON but received HTML.")
      }

      if (!response.ok) {
        const errorData = await response.json()
        throw new Error(errorData.message || "API request failed")
      }

      return await response.json()
    } catch (error) {
      console.error("API DELETE request failed:", error)
      throw error
    }
  }
}

// Make the ApiClient available globally
window.ApiClient = ApiClient
