create user "formularios"@"localhost" identified by "rr";
create user "formularios"@"%" identified by "rr";
grant all on formularios . * to "formularios"@"localhost";
grant all on formularios . * to "formularios"@"%";
grant FILE on * . * to "formularios"@"localhost";
grant FILE on * . * to "formularios"@"%";
flush privileges;