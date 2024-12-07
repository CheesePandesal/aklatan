window.onload = () => displayBooks();

let idToEdit = null;
const closeBtn = document.getElementById("close-btn");
const bookForm = document.getElementById("bookForm");
const titleInput = document.getElementById("titleInput");
const authorInput = document.getElementById("authorInput");
const publisherInput = document.getElementById("publisherInput");
const publicationYearInput = document.getElementById("publicationYearInput");
let bookStatus = "Available";
// Use the hideModal function
// Use the hideModal function and add custom code
closeBtn.addEventListener("click", () => {
  resetForm();
});
async function getBooks() {
  //check id if defined

  const endpoint = "../../api/book.php";

  //return fetch
  return fetch(endpoint).then((response) => {
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    return response.json();
  });
}
async function displayBooks(searchQuery) {
  const books = await getBooks(null, searchQuery);
  const bookDisplay = document.getElementById("book-record");
  bookDisplay.innerHTML = "";

  books.forEach((book) => {
    const bookHTML = `
      <tr class="book-row" id="${book.book_id}">
        <td class="book-id">${book.book_id}</td>
        <td class="book-title">${book.book_title}</td>
        <td class="book-author">${book.book_author}</td>
        <td class="book-publisher">${book.book_publisher}</td>
        <td class="book-publication-year">${book.book_publication_year}</td>
        <td class="book-status" style="color: ${
          book.book_status === "Available" ? "#4aa86f" : "#f65867"
        };">${book.book_status}</td>
        <td class="book-borrowed-date">${
          book.book_borrowed_date == null ? "" : book.book_borrowed_date
        }</td>
        <td class="book-returned-date">${
          book.book_returned_date == null ? "" : book.book_returned_date
        }</td>
        <td class="book-actions">
          <button class="edit-btn" onclick="ediStatus(${book.book_id})" >
          ${book.book_status === "Available" ? "Borrow" : "Return"}
        </td>
      </tr>
    `;
    bookDisplay.innerHTML += bookHTML;
  });
}
/////////////////////////////////////////////////////// PUT ////////////////////////////////////////////////
//function to set edit Books, with id
function ediStatus(bookId) {
  fetch(`../../api/borrow-return-book.php?bookId=${bookId}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        Swal.fire({
          icon: "success",
          title: "Book Status Updated!",
          text: data.message,
          confirmButtonColor: "#34C759",
        });
        displayUpdatedData(data.data);
      } else {
        console.error("Error updating book status:", data.error);
      }
    })
    .catch((error) => console.error("Error:", error));
}

function displayUpdatedData(book) {
  //find tr with id selected id
  //then change its content to updated data
  const bookDisplay = document.getElementById(`${book.book_id}`);
  bookDisplay.innerHTML = `
      <td class="book-id">${book.book_id}</td>
                                <td class="book-title">
                                ${book.book_title}
                                </td>
                                <td class="book-author">
                                ${book.book_author}
                                </td>
                                <td class="book-publisher">
                                ${book.book_publisher}
                                </td>
                                <td class="book-publication-year">
                                ${book.book_publication_year}
                                </td>
                                <td class="book-status" style="color: ${
                                  book.book_status === "Available"
                                    ? "#4aa86f"
                                    : "#f65867"
                                };">${book.book_status}</td>

                                <td class="book-borrowed-date">${
                                  book.book_borrowed_date == null
                                    ? ""
                                    : book.book_borrowed_date
                                }</td>
                                <td class="book-returned-date">${
                                  book.book_returned_date == null
                                    ? ""
                                    : book.book_returned_date
                                }</td>
                                <td class="book-actions">
          <button class="edit-btn" onclick="ediStatus(${book.book_id})">
          ${book.book_status === "Available" ? "Borrow" : "Return"}
        </button>
        </td>
      </tr>
    `;
  //clear form and set button to save not update
}
function displayNewData(book) {
  const bookDisplay = document.getElementById("book-record");
  let row = `
          <tr class="book-row" id="${book.book_id}">
                                <td class="book-id">${book.book_id}</td>
                                <td class="book-title">
                                ${book.book_title}
                                </td>
                                <td class="book-author">
                                ${book.book_author}
                                </td>
                                <td class="book-publisher">
                                ${book.book_publisher}
                                </td>
                                <td class="book-publication-year">
                                ${book.book_publication_year}
                                </td>
                                <td class="book-status" style="color: ${
                                  book.book_status === "Available"
                                    ? "#4aa86f"
                                    : "#f65867"
                                };">${book.book_status}</td>

                                <td class="book-borrowed-date">${
                                  book.book_borrowed_date == null
                                    ? ""
                                    : book.book_borrowed_date
                                }</td>
                                <td class="book-returned-date">${
                                  book.book_returned_date == null
                                    ? ""
                                    : book.book_returned_date
                                }</td>
                                <td class="book-actions">
                                    <button class="edit-btn" onclick="ediStatus(${
                                      book.book_id
                                    })">
                                        Edit
                                    </button>
                                    <button class="delete-btn" onclick="deleteBook(${
                                      book.book_id
                                    })">
                                        Delete
                                    </button>

                                </td>
                            </tr>
      `;
  bookDisplay.innerHTML += row;
}

/////////////////////////////////////////////////////// DELETE ////////////////////////////////////////////////

let searchTimeout = null;
document.getElementById("search-input").addEventListener("input", () => {
  const searchQuery = document.getElementById("search-input").value;
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    displayBooks(searchQuery);
  }, 500);
});
