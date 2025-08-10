jQuery(document).ready(function($){
    $(`#clip-search-form`).on('submit', function(e){
        e.prevemtDefault();
        let formData = new FormData(this);
        formData.append('action', 'clip_search');
        $.ajax({
            url: clip_ajax.ajax_url,
            type: 'POST',
            date: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $(`#clip-results`).html(`<p>Searching...</p>`);
            },
            success: function(response) {
                let output = '<ul>';
                if (response.success && response.data.length > 0) {
                    response.data.forEach(item => {
                        output += `
                        <li>
                            <a href="${item.url}">
                                <img src="${item.thumbnail}" alt="${item.title}">
                                <span>${item.title}</span>
                                <span class="price">${item.price}</span>
                            </a>
                        </li>
                        `;
                    });
                } else {
                    output += '<li>No matching products found.</li>';
                }
                output += '</ul>';
                $(`#clip-results`).html(output);
            }
        })
    });
})