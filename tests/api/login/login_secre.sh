#login_api
echo "$myapi"


# Intenta hacer login con la secretaria y obtener el archivo logintoken.txt con formato id=9&token=xxxxxx
curl -o /Library/WebServer/Documents/jumanja.net/sisga/tests/api/login/logintoken.txt --data "usuario=secretaria&password=secretaria&format=text" "$myapi"/login

