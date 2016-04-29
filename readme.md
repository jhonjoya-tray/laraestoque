# LaraEstoque

#Configuração do projeto

- Já com o ambiente configurado, instale as dependencias do projeto via ``composer install``
- Copie o arquivo ``.env.example`` para ``.env`` neste arquivo aponte para o banco de dados a ser utilizado
- Realize a restauração do banco, utilizando o arquivo ``estoque.sql`` disponível neste projeto

#Congigurnando o vhost

- Crie o arquivo laraestoque.conf em /etc/nginx/conf.d , com o conteúdo informado abaixo:

```
server {
  server_name laraestoque.traylabs.php4devs;
  root        /var/www/laraestoque/public;
  index       index.php;

  client_max_body_size 100M;
  fastcgi_read_timeout 1800;

  location / {
    try_files $uri $uri/ /index.php$is_args$args;
  }

  location /status {
     access_log off;
     allow 172.17.0.0/16;
     deny all;
     include /etc/nginx/fastcgi_params;
     fastcgi_param SCRIPT_FILENAME /status;
     fastcgi_pass 127.0.0.1:9000;
  }

  location /ping {
     access_log off;
     allow all;
     include fastcgi_params;
     fastcgi_param SCRIPT_FILENAME /ping;
     fastcgi_pass 127.0.0.1:9000;
  }

  location ~* \.(js|css|png|jpg|jpeg|gif|ico)$ {
    expires       max;
    log_not_found off;
    access_log    off;
  }

  location ~ \.php$ {
    try_files     $uri =404;
    include       fastcgi_params; 
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_pass  127.0.0.1:9000;
    # fastcgi_pass  unix:/var/run/hhvm/hhvm.sock;
  } 
} 
```
- Edite o arquivo /etc/hosts e adicione o apontamento

```
127.0.0.1  http://laraestoque.traylabs.php4devs/
```

- Reinicie o container
- Acesse a url http://laraestoque.traylabs.php4devs/
