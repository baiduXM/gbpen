# G宝盆

克隆之后初始化工作：

## 仅本地调试：

 1. 复制`app/config/database.init.php`文件为`app/config/database.php`
 2. 修改`database.php`文件内数据库连接字符串

## 需调试服务器：

 1. 复制`bootstrap/start.init.php`文件为`bootstrap/start.php`
 2. 修改`start.php`文件29行本地电脑主机名
 3. 参照`doc/启用本地模拟服务器环境调试.txt`
