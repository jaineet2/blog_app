$(document).ready(function() {
    function loadArticles() {
        $.ajax({
            url: 'index.php',
            method: 'GET',
            data: {
                fetch_articles: 1,
                search: $('#searchInput').val(),
                sort: $('#sortSelect').val(),
                category: $('#categorySelect').val(),
                date: $('#dateInput').val()
            },
            dataType: 'json',
            success: function(articles) {
                let html = '';
                if (articles.length === 0) {
                    html = `
                        <div class="col-12 no-articles animate__animated animate__fadeIn">
                            <i class="fas fa-search mb-3"></i>
                            <h3>No articles found</h3>
                            <p>Try adjusting your search or filter to find what you're looking for.</p>
                        </div>
                    `;
                } else {
                    articles.forEach(function(article) {
                        html += `
                            <div class="col animate__animated animate__fadeIn">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">${article.title}</h5>
                                        <p class="card-text">${article.description.substring(0, 100)}...</p>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>${new Date(article.created_at).toLocaleDateString()}
                                            </small>
                                        </p>
                                        <span class="badge bg-primary">${article.category}</span>
                                    </div>
                                    <div class="card-footer bg-transparent border-0">
                                        <a href="edit_article.php?id=${article.id}" class="btn btn-sm btn-outline-primary me-2">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger deleteBtn" data-id="${article.id}">
                                            <i class="fas fa-trash-alt me-1"></i>Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                $('#articleList').html(html);
            }
        });
    }

    $('#searchInput, #sortSelect, #categorySelect, #dateInput').on('keyup change', loadArticles);

    let deleteId;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    $(document).on('click', '.deleteBtn', function() {
        deleteId = $(this).data('id');
        deleteModal.show();
    });

    $('#confirmDelete').click(function() {
        $.ajax({
            url: 'index.php',
            method: 'POST',
            data: { id: deleteId, delete_article: 1 },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    loadArticles();
                } else {
                    showAlert(response.message, 'danger');
                }
                deleteModal.hide();
            }
        });
    });

    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        $('#alertContainer').html(alertHtml);
        setTimeout(() => {
            $('#alertContainer').html('');
        }, 1000);
    }

    loadArticles();

    // For add_article.php and edit_article.php
    $('#title').on('input', function() {
        let slug = this.value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
        $('#slug').val(slug);
    });
});