# SwShop
基于 PHP/Swoole 的高性能电商平台

# nginx config
    server {
        listen 80;
        server_name domain;
    
        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";
        add_header X-XSS-Protection "1; mode=block";
    
        charset utf-8;
    
        location / {
            proxy_pass http://127.0.0.1:9501;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarder-For $proxy_add_x_forwarded_for;
        }
    
        location = /favicon.ico {
            access_log off;
            log_not_found off;
        }
    
        location = /robots.txt {
            access_log off;
            log_not_found off;
        }
    
        location ~* /\.(?!well-known).* {
            deny all;
        }
    
        location ~* /\.ht {
            deny all;
        }
    }

