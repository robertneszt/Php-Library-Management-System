async function getbook() {
  const search = document.getElementById("search").value;
  const output = document.getElementById("output");
  output.innerHTML = " ";
  const outputErr = document.getElementById("outputError");
  outputErr.innerHTML = " ";
  const key = `AIzaSyAIJgmvfVKK-ABqD31Taqidp9BI-5WJT4c`;

  if (!search || search == null) {
    outputErr.innerHTML = "<h2>Please enter a valid info to search </h2>";
    console.log("Please enter a valid book name");
  } else {
    const result = await fetch(
      `https://www.googleapis.com/books/v1/volumes?q=${search}&key=${key}`
    );
    result
      .json()
      .then((json) => {
        // limiting top 10 results per search
        var limit = 9;

        if (json.items.length == 0) {
          outputErr.innerHTML =
            "<h2>No info found please enter correct info to search </h2>";
        }

        for (let i = 0; i <= json.items.length; i++) {
          let index = i;
          let title = json.items[i].volumeInfo.title.toUpperCase();
          let ISBN = json.items[i].volumeInfo.industryIdentifiers[0].identifier;
          let authors = json.items[i].volumeInfo.authors;
          let publisher = json.items[i].volumeInfo.publisher;
          let thumbnail = json.items[i].volumeInfo.imageLinks.smallThumbnail;
          let pubDate = json.items[i].volumeInfo.publishedDate;
          console.log(title);
          console.log(ISBN, authors, publisher, thumbnail, pubDate);
          output.innerHTML += createOutput(
            index,
            title,
            ISBN,
            authors,
            publisher,
            thumbnail,
            pubDate
          );

          if (i == limit) {
            break;
          }
        }
      })
      .catch((ErrorEvent) => {});
  }
}

function createOutput(
  index,
  title,
  isbn,
  authors,
  publisher,
  thumbnail,
  pubDate
) {
  var htmlCard = ` <form action=bookstore.php method="post">
    <div class="col mb-5 form-group">
    <div class="card h-100 form-group">
        <!-- book image-->
        <img class="card-img-top"  src="${thumbnail}" alt="..." />
        <!-- book details-->
        <div class="form-group card-body p-4">
            <div class="">
                <!-- book title-->
                <input type="hidden" name="index" class="form-control" value="${index}">
                <h5 class="card-title" >${title}</h5>
                <input type="hidden" name="title" class="form-control" value="${title}">

                <p class="card-text">ISBN: ${isbn}</p>
                <input type="hidden" name="isbn" class="form-control" value="${isbn}">

                <p class="card-text" >Authors: ${authors}</p>
                <input type="hidden" name="authors" class="form-control" value="${authors}">

                <p class="card-text">Publisher: ${publisher}</p>
                <input type="hidden" name="publisher" class="form-control" value="${publisher}">

                <p class="card-text">Publish on: ${pubDate}</p>
                <input type="hidden" name="date" class="form-control" value="${pubDate}">
            </div>
        </div>
        <!-- book actions-->
        <div class="form-group text-center card-footer p-4 pt-0 border-top-0 bg-transparent">
            
            <input type="submit" name="add_to_cart" class="btn btn-outline-dark mt-auto" value="Borrow book">
        </div>
    </div>
</div> 
</form>`;

  return htmlCard;
}

const serachbtn = document.getElementById("serachbtn");
serachbtn.addEventListener("click", getbook);

{
}
