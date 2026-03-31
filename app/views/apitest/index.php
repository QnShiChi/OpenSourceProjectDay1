<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Test Docs - Swagger Clone</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <style>
        body { background-color: #fafafa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .method-badge { min-width: 80px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 14px;}
        .endpoint-row { cursor: pointer; border-radius: 4px; border: 1px solid #ddd; margin-bottom: 12px; transition: all 0.2s; background: white;}
        
        .endpoint-row.GET { border-color: #61affe; }
        .endpoint-row.GET .badge { background-color: #61affe; color: white; }
        .endpoint-row.GET .endpoint-header { background-color: rgba(97, 175, 254, 0.1); }
        
        .endpoint-row.POST { border-color: #49cc90; }
        .endpoint-row.POST .badge { background-color: #49cc90; color: white; }
        .endpoint-row.POST .endpoint-header { background-color: rgba(73, 204, 144, 0.1); }
        
        .endpoint-row.PUT { border-color: #fca130; }
        .endpoint-row.PUT .badge { background-color: #fca130; color: white; }
        .endpoint-row.PUT .endpoint-header { background-color: rgba(252, 161, 48, 0.1); }
        
        .endpoint-row.DELETE { border-color: #f93e3e; }
        .endpoint-row.DELETE .badge { background-color: #f93e3e; color: white; }
        .endpoint-row.DELETE .endpoint-header { background-color: rgba(249, 62, 62, 0.1); }

        .endpoint-header { padding: 10px 15px; border-radius: 4px; display: flex; align-items: center; }
        .endpoint-header:hover { opacity: 0.9; }
        .path { font-family: monospace; font-size: 16px; font-weight: bold; color: #3b4151; margin-right: 15px; }
        .summary { color: #3b4151; font-size: 14px; }
        
        pre { background: #2b2b2b; color: #a9b7c6; border-radius: 5px; padding: 15px; margin-top: 10px; max-height: 400px; overflow-y: auto; font-size: 14px;}
        .badge.bg-success { background-color: #28a745 !important; color: white;}
        .badge.bg-danger { background-color: #dc3545 !important; color: white;}
        .badge.bg-warning { background-color: #ffc107 !important; color: black;}
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4 shadow-sm">
        <a class="navbar-brand text-success font-weight-bold" href="#">
            <i class="fas fa-paper-plane mr-2"></i> OverFlow Shop 
        </a>
        <a href="/PhanDuongQuocNhat/" class="text-white text-decoration-none"><i class="fas fa-home"></i> Trở về Shop</a>
    </nav>

    <div class="container mb-5">
        <div class="mb-4">
            <h2 class="font-weight-bold" style="color: #3b4151;">Giao diện Tương tác API (API Testing)</h2>
            <p class="text-muted">Được rèn đúc 100% bằng jQuery + Bootstrap, kết nối trực tiếp đến Controller RESTful API PHP.</p>
        </div>
        
        <h4 class="mb-3 mt-4 border-bottom pb-2">1. Cụm API Sản phẩm (Product)</h4>
        <div id="product-api-container"></div>
        
        <h4 class="mb-3 mt-5 border-bottom pb-2">2. Cụm API Danh mục (Category)</h4>
        <div id="category-api-container"></div>
    </div>
    
    <script>
        const baseUrl = '/PhanDuongQuocNhat/api';
        
        // Cấu hình dữ liệu giống hệt chuẩn OpenAPI (Swagger)
        const productEndpoints = [
            { path: '/product', method: 'GET', summary: 'Lấy toàn bộ danh sách Sản phẩm', params: [] },
            { path: '/product/{id}', method: 'GET', summary: 'Lấy thông tin 1 Sản phẩm theo ID', params: [{name: 'id', type: 'text', in: 'path', required: true}] },
            { path: '/product', method: 'POST', summary: 'Thêm mới một Sản phẩm (hoạt động với form-data)', params: [
                {name: 'name', type: 'text', in: 'body'},
                {name: 'description', type: 'text', in: 'body'},
                {name: 'price', type: 'number', in: 'body'},
                {name: 'category_id', type: 'text', in: 'body'},
                {name: 'image', type: 'file', in: 'body'}
            ]},
            { path: '/product/{id}', method: 'PUT', summary: 'Cập nhật Sản phẩm', params: [
                {name: 'id', type: 'text', in: 'path', required: true},
                {name: 'name', type: 'text', in: 'body'},
                {name: 'description', type: 'text', in: 'body'},
                {name: 'price', type: 'number', in: 'body'},
                {name: 'category_id', type: 'text', in: 'body'},
                {name: 'image', type: 'file', in: 'body'}
            ]},
            { path: '/product/{id}', method: 'DELETE', summary: 'Xóa Sản phẩm', params: [{name: 'id', type: 'text', in: 'path', required: true}] }
        ];

        const categoryEndpoints = [
            { path: '/category', method: 'GET', summary: 'Lấy toàn bộ danh sách Danh mục', params: [] },
            { path: '/category/{id}', method: 'GET', summary: 'Lấy thông tin 1 Danh mục theo ID', params: [{name: 'id', type: 'text', in: 'path', required: true}] },
            { path: '/category', method: 'POST', summary: 'Thêm mới một Danh mục', params: [
                {name: 'name', type: 'text', in: 'body'},
                {name: 'description', type: 'text', in: 'body'},
                {name: 'image', type: 'file', in: 'body'}
            ]},
            { path: '/category/{id}', method: 'PUT', summary: 'Cập nhật Danh mục', params: [
                {name: 'id', type: 'text', in: 'path', required: true},
                {name: 'name', type: 'text', in: 'body'},
                {name: 'description', type: 'text', in: 'body'},
                {name: 'image', type: 'file', in: 'body'}
            ]},
            { path: '/category/{id}', method: 'DELETE', summary: 'Xóa Danh mục', params: [{name: 'id', type: 'text', in: 'path', required: true}] },
        ];

        $(document).ready(function() {
            // Hàm xuất giao diện Endpoint
            function renderEndpoints(endpoints, containerId, prefixIdx) {
                let html = '';
                endpoints.forEach((ep, index) => {
                    let uniqueId = 'ep_' + prefixIdx + '_' + index;
                    
                    let paramRows = '';
                    if(ep.params.length > 0) {
                        paramRows = '<table class="table table-sm mt-3 table-borderless"><thead><tr class="border-bottom"><th>Tên Trừơng (Name)</th><th>Giá trị Truyền (Value)</th></tr></thead><tbody>';
                        ep.params.forEach(p => {
                            let reqHTML = p.required ? '<span class="text-danger font-weight-bold">*</span>' : '';
                            let inputHtml = p.type === 'file' 
                                ? `<input type="file" class="form-control-file param-input" data-name="${p.name}" data-in="${p.in}">`
                                : `<input type="text" class="form-control form-control-sm param-input" data-name="${p.name}" data-in="${p.in}" placeholder="Trống nếu k update">`;
                            
                            paramRows += `<tr>
                                <td width="30%" class="align-middle">
                                    <div class="font-weight-bold">${p.name} ${reqHTML}</div>
                                    <div class="text-muted small">kiểu: ${p.type} | vùng: ${p.in}</div>
                                </td>
                                <td class="align-middle">${inputHtml}</td>
                            </tr>`;
                        });
                        paramRows += '</tbody></table>';
                    } else {
                        paramRows = '<div class="alert alert-secondary mt-3 mb-0 border-0 bg-light text-dark">API này không yêu cầu đối số tham số nào.</div>';
                    }

                    html += `
                    <div class="endpoint-row ${ep.method}">
                        <div class="endpoint-header collapsed" data-toggle="collapse" data-target="#${uniqueId}">
                            <span class="badge ${ep.method} method-badge mr-3 py-1">${ep.method}</span>
                            <span class="path">${ep.path}</span>
                            <span class="summary">${ep.summary}</span>
                        </div>
                        <div id="${uniqueId}" class="collapse px-4 pb-4 bg-white border-top">
                            <h6 class="font-weight-bold mt-4 mb-3 border-bottom pb-2 text-secondary">Tham số Đầu vào (Parameters)</h6>
                            <form class="api-form">
                                <input type="hidden" class="hidden-path" value="${ep.path}">
                                <input type="hidden" class="hidden-method" value="${ep.method}">
                                ${paramRows}
                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary px-4 bg-primary border-primary">
                                        Execute <i class="fas fa-bolt ml-1"></i>
                                    </button>
                                </div>
                            </form>
                            
                            <div class="response-area mt-4 p-3 rounded" style="display:none; background-color: #f8f9fa;">
                                <h6 class="font-weight-bold mb-3">Kết quả Trả về (Response)</h6>
                                <div class="mb-2"><strong class="text-secondary">Request URL:</strong> <code class="req-url user-select-all text-dark"></code></div>
                                <div class="mb-3"><strong class="text-secondary">Status Code:</strong> <span class="badge res-status p-2" style="font-size:14px;"></span></div>
                                <pre class="m-0"><code class="res-body"></code></pre>
                            </div>
                        </div>
                    </div>`;
                });
                $('#' + containerId).html(html);
            }

            renderEndpoints(productEndpoints, 'product-api-container', 'P');
            renderEndpoints(categoryEndpoints, 'category-api-container', 'C');

            // Bắt sự kiện Gửi (Execute)
            $('.api-form').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let btn = form.find('button[type="submit"]');
                let resArea = form.siblings('.response-area');
                
                let pathTemplate = form.find('.hidden-path').val();
                let method = form.find('.hidden-method').val();
                
                let formData = new FormData();
                let actualPath = baseUrl + pathTemplate;
                let hasBody = false;

                // Thay thế tham số path và gom nhóm body
                form.find('.param-input').each(function() {
                    let name = $(this).data('name');
                    let inType = $(this).data('in');
                    let val = $(this).val();
                    
                    if(inType === 'path') {
                        if(val) actualPath = actualPath.replace('{' + name + '}', val);
                    } else if (inType === 'body') {
                        if ($(this).attr('type') === 'file') {
                            let fileData = $(this)[0].files[0];
                            if(fileData) { formData.append(name, fileData); hasBody = true; }
                        } else {
                            if (val !== '') { formData.append(name, val); hasBody = true; }
                        }
                    }
                });

                // Hiệu ứng Đang tải
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Executing...');
                resArea.slideDown();
                resArea.find('.res-body').text('Đang chờ phản hồi từ Server...');
                resArea.find('.res-status').attr('class', 'badge res-status bg-secondary').text('Pending...');
                resArea.find('.req-url').text('http://' + window.location.host + actualPath);

                // Cấu hình AJAX
                let ajaxSettings = {
                    url: actualPath,
                    // Nếu là form-data nhưng Method là PUT/DELETE, ta phải dùng POST như đã setup trên backend để vượt giới hạn PHP POST variables
                    method: (method === 'PUT' || method === 'DELETE') ? 'POST' : method,
                    dataType: 'json',
                    success: function(data, textStatus, xhr) {
                        displayResponse(resArea, xhr.status, data);
                    },
                    error: function(xhr) {
                        let data;
                        try { data = JSON.parse(xhr.responseText); } catch(e) { data = xhr.responseText; }
                        displayResponse(resArea, xhr.status, data);
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('Execute <i class="fas fa-bolt ml-1"></i>');
                    }
                };

                // Tricking the Method Override qua Header và Query (Báo cho PHP Controller biết method thật sự)
                if (method === 'PUT' || method === 'DELETE') {
                    ajaxSettings.url += '?_method=' + method;
                    ajaxSettings.beforeSend = function(xhr) {
                        xhr.setRequestHeader('X-HTTP-Method-Override', method);
                    };
                }

                // Append formdata nếu CÓ truyền tham số Body
                if (hasBody && (method === 'POST' || method === 'PUT')) {
                    ajaxSettings.data = formData;
                    ajaxSettings.processData = false;
                    ajaxSettings.contentType = false;
                }

                // Nếu GET không có body, mặc định
                $.ajax(ajaxSettings);
            });

            // Hàm format Response Box
            function displayResponse(resArea, status, data) {
                let badgeClass = 'badge ';
                if(status >= 200 && status < 300) badgeClass += 'bg-success';
                else if(status >= 400 && status < 500) badgeClass += 'bg-warning text-dark';
                else badgeClass += 'bg-danger';
                
                resArea.find('.res-status').attr('class', badgeClass + ' res-status').text(status + ' CẢM GIÁC THÀNH CÔNG');
                if(status >= 400) resArea.find('.res-status').text(status + ' LỖI REQUEST');
                if(status >= 500) resArea.find('.res-status').text(status + ' LỖI SERVER');
                if(status == 200 || status == 201) resArea.find('.res-status').text(status + ' OK');
                
                if(typeof data === 'object') {
                    resArea.find('.res-body').text(JSON.stringify(data, null, 4));
                } else {
                    resArea.find('.res-body').text(data);
                }
            }
        });
    </script>
</body>
</html>
