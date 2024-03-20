## **API Documentation for ShortenedURL**

**Base URL:** [http://shorturls.zictracks.com/](http://shorturls.zictracks.com/)

**Methods:**

**GET:**

*   Retrieves all shortened URLs. (Endpoint: `/shorturls?key=userid`)
*   **Response:**
*   Status code: 200 (Success)
*   Body: JSON object with the following properties:
*   `status` (boolean): True if successful, False otherwise.
*   `urls` (array): Array of shortened URL objects (if successful). Each object contains the following properties:
*   `id` (integer): Unique identifier of the shortened URL.
*   `ActualUrl` (string): The original URL.
*   `UniqueIdentifier` (string): The shortened identifier for the URL.
*   `created_at` (string): Date and time the URL was shortened (format: YYYY-MM-DD HH:MM:SS).
*   **Example Valid Response:**

JSON

{
  "status": true,
  "urls": \[
    {
      "id": 7,
      "ActualUrl": "https://trackss.vercel.app",
      "UniqueIdentifier": "b10c0901",
      "created\_at": "2024-02-26 04:30:47"
    },
    {
      "id": 8,
      "ActualUrl": "https://tracks.vercel.app/auth/login",
      "UniqueIdentifier": "ee1e0995",
      "created\_at": "2024-02-28 10:02:52"
    }
  \]
}

**POST:**

*   Creates a new shortened URL. (Endpoint: `/shorturl?key=userid`)
*   **Request Body:**
*   JSON object with the following property:
*   `ActualUrl` (string): The actual URL to be shortened.
*   `Title`: (string): Link title.
*   `custom_alias`: (string: optional) specify is you want a custom url
*   **Response:**
*   Status code:
*   201 (Created) - URL shortened successfully.
*   422 (Unprocessable Entity) - Missing required field(s).
*   500 (Internal Server Error) - Creation failed.
*   Body: JSON object with the following properties (if successful):
*   `status` (boolean): True.
*   `url` (object): The newly created shortened URL object, containing properties like `UniqueIdentifier` and `ActualUrl`.

**PUT:** (Requires URL ID in the path)

*   Updates an existing shortened URL. (Endpoint: `/` followed by the URL ID)
*   **Request Body:**
*   JSON object with properties to update (e.g., `ActualUrl`).
*   **Response:**
*   Status code:
*   200 (OK) - URL updated successfully.
*   422 (Unprocessable Entity) - Missing required field(s).
*   500 (Internal Server Error) - Update failed.
*   Body: JSON object depending on the response status.

**DELETE:** (Requires URL ID in the path)

*   Deletes an existing shortened URL. (Endpoint: `/` followed by the URL ID)
*   **Response:**
*   Status code:
*   200 (OK) - URL deleted successfully.
*   500 (Internal Server Error) - Deletion failed.
*   Body: JSON object depending on the response status.

**Error Handling:**

The API uses different HTTP status codes and JSON messages to communicate errors. Refer to the specific method descriptions for details.
