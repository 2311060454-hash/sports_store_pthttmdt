# HƯỚNG DẪN TRIỂN KHAI DỰ ÁN LÊN RENDER & AIVEN MYSQL (DOCKER)

Tài liệu này tổng hợp toàn bộ cấu trúc và các bước triển khai chuẩn hóa theo đúng yêu cầu của giảng viên cho đề tài:
**“PHÁT TRIỂN HỆ THỐNG THƯƠNG MẠI ĐIỆN TỬ CHO CỬA HÀNG DỤNG CỤ THỂ THAO”**.

---

## 1. Danh sách các thành phần & file triển khai đã bổ sung

| Thành phần | Đường dẫn tập tin | Chức năng |
| :--- | :--- | :--- |
| **Dockerfile** | `Dockerfile` | Multi-stage build (Alpine 3.19 + PHP 8.2-FPM + Nginx + Tini init + opcache). |
| **Docker Ignore** | `.dockerignore` | Loại trừ `vendor`, `node_modules`, `.env`, certs, tests khỏi image Docker. |
| **Nginx Config** | `docker/nginx.conf` | Cấu hình máy chủ web lắng nghe port `$PORT`, route toàn bộ traffic qua `/index.php`. |
| **PHP Runtime** | `docker/php.ini` | Tối ưu hóa bộ nhớ (128M), tắt display errors, bật Opcache production. |
| **PHP-FPM** | `docker/php-fpm.conf` | Quản lý FastCGI worker theo chế độ `ondemand` tiết kiệm RAM (Render Free). |
| **Entrypoint** | `docker/entrypoint.sh` | Điều phối chứng chỉ Aiven CA, chạy migrations, seeders, tối ưu cache và khởi động song song Nginx + PHP-FPM. |
| **Kiểm tra CA** | `docker/check-ca.php` | Xác thực file chứng chỉ CA Aiven MySQL có thể đọc được bởi user `www-data`. |
| **Biến môi trường mẫu** | `docker/render.env.example` | Mẫu biến môi trường chi tiết để nhập trực tiếp lên mục Environment của Render. |
| **Cấu hình Seeding Admin**| `config/seeding.php` | Đọc thông tin Admin ban đầu từ môi trường (`SEED_ADMIN_EMAIL`, `SEED_ADMIN_PASSWORD`). |
| **Admin Seeder** | `database/seeders/AdminUserSeeder.php` | Khởi tạo tài khoản admin bảo mật, kiểm tra độ dài mật khẩu >= 12 ký tự, gán email_verified_at. |
| **Database Transaction** | `database/seeders/DatabaseSeeder.php` | Bọc toàn bộ quá trình seed trong `DB::transaction()` và thực hiện `firstOrCreate` tránh trùng lặp. |
| **Health Check & Proxy** | `bootstrap/app.php` | Cấu hình endpoint `/up`, tin cậy reverse proxy (`trustProxies('*')`), miễn trừ CSRF cho webhook. |
| **Tích hợp bên ngoài** | `app/Http/Controllers/IntegrationController.php` | Xử lý MoMo callback/IPN, GHN Webhook, và VNPay callback. |
| **Bộ kiểm thử tự động** | `tests/Feature/DeploymentAndIntegrationsTest.php` | Kiểm thử tự động (PHPUnit) cho `/up`, MoMo IPN, GHN Webhook, Admin Seeder. |

---

## 2. Quy trình triển khai từng bước lên Render & Aiven

### Bước 1: Chuẩn bị cơ sở dữ liệu Aiven MySQL
1. Đăng nhập [Aiven Console](https://console.aiven.io/).
2. Tạo dịch vụ MySQL (chọn gói Free hoặc phù hợp, vị trí gần Việt Nam như Singapore).
3. Đợi trạng thái dịch vụ chuyển sang **RUNNING**.
4. Ghi nhận các thông số kết nối:
   - **Host** (Service URI)
   - **Port** (thường là 10xxx đến 2xxxx)
   - **User**: `avnadmin`
   - **Password**: mật khẩu do Aiven sinh ra
   - **Database**: `defaultdb`
5. Tải chứng chỉ CA về máy (file thường có tên `ca.pem`).

---

### Bước 2: Tạo Web Service trên Render
1. Đăng nhập [Render Dashboard](https://dashboard.render.com/) và kết nối tài khoản GitHub.
2. Chọn **New +** > **Web Service**.
3. Chọn Repository mã nguồn của bạn.
4. Điền cấu hình cơ bản:
   - **Name**: `sports-store` (hoặc tên tùy chọn)
   - **Language**: `Docker`
   - **Branch**: `main` (hoặc `master`)
   - **Region**: `Singapore` (để tối ưu độ trễ với Aiven)
   - **Health Check Path**: `/up`

---

### Bước 3: Đưa chứng chỉ CA vào Render (Secret Files)
1. Trong màn hình cấu hình Web Service, kéo xuống phần **Secret Files**.
2. Nhấn **Add Secret File**:
   - **Filename**: `/etc/secrets/ca.pem`
   - **Contents**: Mở file `ca.pem` vừa tải từ Aiven bằng Notepad, sao chép toàn bộ nội dung và dán vào ô này.

---

### Bước 4: Khai báo biến môi trường (Environment Variables)
Tham khảo file `docker/render.env.example` và nhập các biến sau vào mục **Environment** trên Render:

```env
APP_NAME=sports_store
APP_ENV=production
APP_DEBUG=false
APP_URL=https://YOUR-SERVICE.onrender.com
APP_KEY=base64:YOUR_APP_KEY
TRUSTED_PROXIES=*
DB_CONNECTION=mysql
DB_HOST=YOUR-AIVEN-HOST
DB_PORT=YOUR-AIVEN-PORT
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD=YOUR-AIVEN-PASSWORD
MYSQL_ATTR_SSL_CA=/etc/secrets/ca.pem
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
CACHE_STORE=database
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
LOG_LEVEL=info
PORT=10000
RUN_MIGRATIONS=true
RUN_SEEDERS=true
SEED_ADMIN_NAME="Shop Admin"
SEED_ADMIN_EMAIL=admin@yourdomain.com
SEED_ADMIN_PASSWORD=MatKhauBaoMat@123456
MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME=sports_store
```

> **LƯU Ý QUAN TRỌNG VỀ SEEDER & MẬT KHẨU:**
> 1. `SEED_ADMIN_PASSWORD` phải có độ dài **tối thiểu 12 ký tự**.
> 2. Trong lần triển khai đầu tiên, đặt `RUN_SEEDERS=true` để hệ thống tự tạo bảng và nạp tài khoản admin cùng danh mục, sản phẩm mẫu.
> 3. Sau khi triển khai lần đầu thành công, chuyển `RUN_SEEDERS=false` để các lần deploy tiếp theo diễn ra nhanh hơn và không chạy lại seeder.

---

## 3. Các lệnh kiểm tra chất lượng (Verification Commands)

### 1. Kiểm tra trên máy lập trình (Local PowerShell):
```powershell
# Xóa cache cấu hình
php artisan config:clear

# Kiểm tra danh sách route (đặc biệt các route tích hợp và healthcheck)
php artisan route:list --path=payment
php artisan route:list --path=ghn

# Chạy toàn bộ test suites tự động
php artisan test
```

### 2. Kiểm tra Health Check sau khi Render khởi động thành công:
```powershell
(Invoke-WebRequest -Uri "https://YOUR-SERVICE.onrender.com/up" -UseBasicParsing).StatusCode
```
Kết quả trả về HTTP **200** là ứng dụng đã sẵn sàng phục vụ.

### 3. Đăng nhập trang quản trị:
- Truy cập: `https://YOUR-SERVICE.onrender.com/admin`
- Email: giá trị đã điền ở `SEED_ADMIN_EMAIL`
- Password: giá trị đã điền ở `SEED_ADMIN_PASSWORD`
