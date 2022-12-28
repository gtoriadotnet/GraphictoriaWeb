openssl req -x509 -nodes -new -sha256 -days 1024 -newkey rsa:2048 -keyout RootCA.key -out RootCA.pem -subj "/C=US/CN=VirtuBrick-Local-CA"
openssl x509 -outform pem -in RootCA.pem -out RootCA.crt
openssl req -new -nodes -newkey rsa:2048 -keyout vbrick.key -out vbrick.csr -subj "/C=US/ST=California/L=San Mateo/O=VirtuBrick Local Test/CN=virtubrick.local"
openssl x509 -req -sha256 -days 1024 -in vbrick.csr -CA RootCA.pem -CAkey RootCA.key -CAcreateserial -extfile domains.ext -out vbrick.crt