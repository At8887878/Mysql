# Mysql
一个MySQL封装的类

## 适用场景：
1. 需要快速、简单的开发和简化应用开发的

## 使用步骤（以监听业务库表user为例）：
1. 在config/global.config.php 中配置mysql参数,开启日志
2. 先引入require_once('config/db.php')配置文件
3. $obj = new DB('表名') 定义表名
4. 执行查询操作 $obj->select()


## 联系人：
如果有兴趣再发行，不论修改过与否，或者有任何问题，请联系作者:at8887878@163.com