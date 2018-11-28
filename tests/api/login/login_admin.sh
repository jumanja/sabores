#login_api
echo "$myapi"

# Intenta hacer login con el admin, se espera login ok
curl -o /Library/WebServer/Documents/jumanja.net/sisga/tests/api/login/logintoken.txt --data "usuario=admin&password=webmaster&format=text" "$myapi"/login
