# ZeroC0D3 Docker Imoov

Docker Imoov, run:
```
docker-compose build && docker-compose up
```

## Requirements:
   * [Docker for Linux] (https://docs.docker.com/engine/installation/linux/ubuntu)
   * [Docker Toolbox for Windows & Mac] (https://www.docker.com/products/docker-toolbox)
   * [Docker Compose] (https://docs.docker.com/compose/install/) 
   * [Kitematic] (https://kitematic.com/) 

## Configuration:
   * Environment
     - MYSQL_DATABASE: zeroc0d3_dbname
     - MYSQL_USER: zeroc0d3_user
     - MYSQL_PASSWORD: zeroc0d3_password
     - MYSQL_ROOT_PASSWORD: zeroc0d3_root
   * Using phpMyAdmin
     - Show running container docker
       ```
       docker ps
       ```
     - Show the IP Address container
       ```
       docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' [container_name_or_id]
       ```
       * Read [this](http://stackoverflow.com/questions/17157721/getting-a-docker-containers-ip-address-from-the-host)
     - Open browser: 
       ```
       http://localhost:8080
       ```

## Submodule Imoov:
   * Clone Imoov as submodule
     - Go to path "workspace" docker-imoov
       ```
       cd workspace
       ```  
     - Clone the imoov repository
       ``` 
       git submodule add https://github.com/zeroc0d3/imoov
       ```
     - Go to path "imoov" (workspace/imooov)
       ```
       cd imoov
       ```
   * Run the composer
     ``` 
     composer install
     ```
       
## License
[MIT License](https://github.com/zeroc0d3/docker-imoov/blob/master/LICENSE) (MIT)
