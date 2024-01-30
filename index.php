<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h4>Users</h4>
                            </div>
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                    Add User
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div id="alert"></div>
                        <div id="response"></div>
                        <!-- <table class="table table-bordered m-0">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Ali</td>
                                    <td>ali@gmail.com</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table> -->

                        <!-- <div class="alert alert-info m-0">No record found!</div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once './partials/modals.php' ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        showUsers();

        const addFormElement = document.querySelector("#add-form");
        const addAlertElement = document.querySelector("#add-alert");

        addFormElement.addEventListener("submit", async function(e) {
            e.preventDefault();

            const nameAddElement = document.querySelector("#name-add");
            const emailAddElement = document.querySelector("#email-add");

            let nameAddValue = nameAddElement.value;
            let emailAddValue = emailAddElement.value;

            nameAddElement.classList.remove('is-invalid');
            emailAddElement.classList.remove('is-invalid');

            addAlertElement.innerHTML = "";

            if (nameAddValue == "") {
                nameAddElement.classList.add('is-invalid');
                addAlertElement.innerHTML = alert("Provide your name!", "danger");
            } else if (emailAddValue == "") {
                emailAddElement.classList.add('is-invalid');
                addAlertElement.innerHTML = alert("Provide your email!", "danger");
            } else {
                const data = {
                    name: nameAddValue,
                    email: emailAddValue,
                    submit: 1,
                };

                const response = await fetch('./api/add-user.php', {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.nameError) {
                    nameAddElement.classList.add('is-invalid');
                    addAlertElement.innerHTML = alert(result.nameError, "danger");
                } else if (result.emailError) {
                    emailAddElement.classList.add('is-invalid');
                    addAlertElement.innerHTML = alert(result.emailError, "danger");
                } else if (result.success) {
                    addAlertElement.innerHTML = alert(result.success, "success");
                    nameAddElement.value = "";
                    emailAddElement.value = "";
                    showUsers();
                } else if (result.failure) {
                    addAlertElement.innerHTML = alert(result.failure, "danger");
                } else {
                    addAlertElement.innerHTML = alert("Something went wrong!", "danger");
                }
            }
        });

        async function showUsers() {
            const responseElement = document.querySelector("#response");

            const response = await fetch("./api/show-users.php");
            const result = await response.json();

            if (result.length > 0) {
                let rowsElement = "";
                let sr = 1;
                result.forEach(function(user) {
                    rowsElement += `<tr>
                                    <td>${sr++}</td>
                                    <td>${user.name}</td>
                                    <td>${user.email}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" onclick="editUser(${user.id})" data-bs-target="#editModal">
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" onclick="deleteUser(${user.id})" data-bs-target="#deleteModal">
                                            Delete
                                        </button>
                                    </td>
                                </tr>`;
                });
                responseElement.innerHTML = `<table class="table table-bordered m-0">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                ${rowsElement}
                            </tbody>
                        </table>`;
            } else {
                responseElement.innerHTML = `<div class="alert alert-info m-0">No record found!</div>`;
            }
        }

        let mainId = 0;

        async function editUser(id) {
            mainId = id;
            const data = {
                id: id,
                submit: 1,
            };

            const response = await fetch("./api/show-single.php", {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            const nameEditElement = document.querySelector("#name-edit");
            const emailEditElement = document.querySelector("#email-edit");

            nameEditElement.value = result.name;
            emailEditElement.value = result.email;
        }

        const editFormElement = document.querySelector("#edit-form");
        const editAlertElement = document.querySelector("#edit-alert");

        editFormElement.addEventListener("submit", async function(e) {
            e.preventDefault();

            const nameEditElement = document.querySelector("#name-edit");
            const emailEditElement = document.querySelector("#email-edit");

            let nameEditValue = nameEditElement.value;
            let emailEditValue = emailEditElement.value;

            nameEditElement.classList.remove('is-invalid');
            emailEditElement.classList.remove('is-invalid');

            editAlertElement.innerHTML = "";

            if (nameEditValue == "") {
                nameEditElement.classList.add('is-invalid');
                editAlertElement.innerHTML = alert("Provide your name!", "danger");
            } else if (emailEditValue == "") {
                emailEditElement.classList.add('is-invalid');
                editAlertElement.innerHTML = alert("Provide your email!", "danger");
            } else {
                const data = {
                    name: nameEditValue,
                    email: emailEditValue,
                    id: mainId,
                    submit: 1
                };

                const response = await fetch("./api/edit-user.php", {
                    method: "POST",
                    body: JSON.stringify(data),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.nameError) {
                    nameEditElement.classList.add('is-invalid');
                    editAlertElement.innerHTML = alert(result.nameError, "danger");
                } else if (result.emailError) {
                    emailEditElement.classList.add('is-invalid');
                    editAlertElement.innerHTML = alert(result.emailError, "danger");
                } else if (result.success) {
                    editAlertElement.innerHTML = alert(result.success, "success");
                    showUsers();
                } else if (result.failure) {
                    editAlertElement.innerHTML = alert(result.failure, "danger");
                } else {
                    editAlertElement.innerHTML = alert("Something went wrong!", "danger");
                }
            }
        });

        function deleteUser(id) {
            mainId = id;
        }

        const deleteFormElement = document.querySelector("#delete-form");
        const alertElement = document.querySelector("#alert");

        deleteFormElement.addEventListener("submit", async function(e) {
            e.preventDefault();

            const data = {
                id: mainId,
                submit: 1
            };

            const response = await fetch("./api/delete-user.php", {
                method: "POST",
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                alertElement.innerHTML = alert(result.success, "success");
                showUsers();
            } else if (result.failure) {
                alertElement.innerHTML = alert(result.failure, "danger");
            } else {
                alertElement.innerHTML = alert("Something went wrong!", "danger");
            }
            closeDeleteModal();
        });

        function closeDeleteModal() {
            const modalElement = document.querySelector('#deleteModal');
            const modal = bootstrap.Modal.getInstance(modalElement);

            if (modal) {
                modal.hide();
            }
        }

        function alert(msg, cls) {
            return `<div class="alert alert-${cls} alert-dismissible fade show" role="alert">${msg}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        }
    </script>
</body>

</html>