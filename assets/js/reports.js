window.onload = () => displayBooks();

let idToEdit = null;
const closeBtn = document.getElementById("close-btn");
const bookForm = document.getElementById("bookForm");
const titleInput = document.getElementById("titleInput");
const authorInput = document.getElementById("authorInput");
const publisherInput = document.getElementById("publisherInput");
const publicationYearInput = document.getElementById("publicationYearInput");
const selectedFilter = document.getElementById("selectedFilter");
    
document.getElementById('generatePdfButton').addEventListener('click', async function() {
  try {
      const response = await fetch('../../api/generate_pdf.php?selectedFilter=' + selectedFilter.value, {
          method: 'GET',
      });
      if (response.ok) {
          const blob = await response.blob();
          const url = URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url;
          a.download = 'library_report.pdf';
          a.click();
          URL.revokeObjectURL(url);
      } else {
          console.error('Error generating PDF:', response.statusText);
      }
  } catch (error) {
      console.error('Error:', error);
  }
});
selectedFilter.addEventListener("change", function () {
  const selectedValue = selectedFilter.value;
  // Run your code here, for example:
  console.log("Selected value:", selectedValue);
  // Call your function here, for example:
  displayBooks(selectedValue);
});
// Use the hideModal function
// Use the hideModal function and add custom code
closeBtn.addEventListener("click", () => {
  resetForm();
});
async function getBooks(selectedFilter) {
  //check id if defined
  if (selectedFilter) {
    endpoint = `../../api/reports.php?selectedFilter=${selectedFilter}`;
  } else {
    endpoint = "../../api/reports.php";
  }
  //return fetch
  return fetch(endpoint).then((response) => {
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    return response.json();
  });
}
async function displayBooks(selectedFilter) {
  const books = await getBooks(selectedFilter);
  const bookDisplay = document.getElementById("book-record");
  bookDisplay.innerHTML = "";

  books.forEach((book) => {
    const bookHTML = `
      <tr class="book-row" id="${book.book_id}">
        <td class="book-id">${book.book_id}</td>
        <td class="book-title">${book.book_title}</td>
        <td class="book-author">${book.book_author}</td>
        <td class="book-publisher">${book.book_publisher}</td>
        <td class="book-status" style="color: ${
          book.book_status === "Available" ? "#4aa86f" : "#f65867"
        };">${book.book_status}</td>
        <td class="book-borrowed-date">${
          book.book_borrowed_date == null ? "" : book.book_borrowed_date
        }</td>
        <td class="book-returned-date">${
          book.book_returned_date == null ? "" : book.book_returned_date
        }</td>
      </tr>
    `;
    bookDisplay.innerHTML += bookHTML;
  });
}
/////////////////////////////////////////////////////// PUT ////////////////////////////////////////////////
//function to set edit Books, with id
async function editBook(bookId) {
  //assign id to global id variable
  idToEdit = bookId;
  //get record using id

  const book = await getBooks(bookId, null);
  modalContainer.style.display = "flex";

  // const book = await getBooks(bookId);
  //populate the form using response/record
  document.getElementById("modal-title").innerHTML = "Update Book";
  titleInput.value = book[0].book_title;
  authorInput.value = book[0].book_author;
  publisherInput.value = book[0].book_publisher;
  publicationYearInput.value = book[0].book_publication_year;
  bookStatus = book[0].book_status;
  //let's change the button to update, to make it dynamic
  document.getElementById("btn").innerHTML = `
    <input type="button" onclick="resetForm()" class="cancel-btn" value="Cancel" style="width: 50%;">
    <input type="submit" value="Update Book" style="width: 50%;">
`;
}

function resetForm() {
  idToEdit = null;
  modalContainer.style.display = "none";
  bookForm.reset();
  document.getElementById("modal-title").innerHTML = "Add New Book";
  document.getElementById("btn").innerHTML = `
    <input type="submit" value="Add Book">
`;
}
bookForm.addEventListener("submit", (e) => {
  e.preventDefault();

  const title = titleInput.value.trim();
  const author = authorInput.value.trim();
  const publisher = publisherInput.value.trim();
  const publishYear = publicationYearInput.value.trim();

  if (!title || !author || !publisher || !publishYear) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Please fill out all fields!",
      confirmButtonColor: "#f65867",
    });
    return;
  }
  //prepare data to submit
  const dataToSubmit = {
    book_title: title,
    book_author: author,
    book_publisher: publisher,
    book_publication_year: publishYear,
    book_status: bookStatus,
  };
  console.log(dataToSubmit);
  //send prepared data to the server using this functions
  //if idToEdit was assigned, update record, if null, add record
  if (idToEdit) {
    updateBook(dataToSubmit);
  } else {
    insertData(dataToSubmit);
  }
});

async function updateBook(dataToSubmit) {
  Swal.fire({
    title: "Update Book?",
    text: "Are you sure you want to update this book?",
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#f65867",
    confirmButtonText: "Yes, update book!",
    cancelButtonColor: "#5d5e66",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(`../../api/book.php?bookId=${idToEdit}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dataToSubmit),
      })
        .then((response) => response.json())
        .then((data) => {
          console.log(data);
          if (data.success) {
            Swal.fire({
              icon: "success",
              title: "Book Updated!",
              text: "Book has been updated successfully!.",
              confirmButtonColor: "#34C759",
            });
            // You can also reset the form here if you want
            displayUpdatedData(data.data);
            resetForm();
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Failed to update book. Please try again.",
              confirmButtonColor: "#f65867",
            });
          }
        })
        .catch((error) => console.error("Error:", error));
    }
  });
}

function displayUpdatedData(book) {
  //find tr with id selected id
  //then change its content to updated data
  const bookDisplay = document.getElementById(`${idToEdit}`);
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

                                <td class="book-actions">
                                    <button class="edit-btn" onclick="editBook(${
                                      book.book_id
                                    })">
                                        Edit
                                    </button>
                                    <button class="delete-btn" onclick="deleteBook(${
                                      book.book_id
                                    })">
                                        Delete
                                    </button>

                                </td>`;
  //clear form and set button to save not update
  resetForm();
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
                                
                                <td class="book-status" style="color: ${
                                  book.book_status === "Available"
                                    ? "#4aa86f"
                                    : "#f65867"
                                };">${book.book_status}</td>

                                <td class="book-actions">
                                    <button class="edit-btn" onclick="editBook(${
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
