PHP进阶之数据库设计
=======

##第八节 分表  

**引言**

我记得之前在看discuz源码的时候，看到了一堆表，大概结构如下：

> dz\_attachment\_0  
> dz\_attachment\_1  
> dz\_attachment\_....

如果一个表，它的数据量非常大，超过了我们能够处理的范围。例如，mysql里面，如果一个表的数据条数超过1kw的时候，性能会明显下降。那么，这种情况下，我们考虑使用分表。

**场景**

假设我们有一个图片存储服务，用户可以上传图片，然后管理图片，可以为外部提供外联。

> create table images (id, type, name, raw);

假设，我们目标是支持100w条的数据；再假如，我们要支持1000w条数据，你的设计又是怎么样的？

目的：为了表的性能，因此，我们考虑分表。

**分表的方式**

1. 水平分表
2. 垂直分表

> create table images (id, type, name);  
> create table images\_raw (id, raw);

一般用在：一个表的字段很多的情况下。按业务来拆分，根据业务的不同来进行拆分；按查询来分，根据sql的频率，将各个表的频率平均化。

> create table images\_0(id, type, name, raw);  
> create table images\_1(id, type, name, raw);

它适用于数据量很大的情况。

这种情况我们又有两种拆分方式：hash拆，增量拆。

hash拆表

假设我们有1kw条数据，我们计划每个表存<100w的数据，这时，我们就按id取模:i = id % 16。

当id为：35，i=35%16，i=3，images_3，insert。。。这张表。

增量拆

id<10w的时候，写入表：images\_0；  
10w<id<20w的时候，写入表：images\_1;

**需要考虑的问题**

1. 需要额外的业务开发逻辑，用来处理分表的情况
2. 仅适合k-v查询，select ... from ... where id = (in) ...的情况；额外建立一个表：(id, download\_count），查询的时候先查询这个关系表：select ... from ... where ... order by download\_count desc limit 10；然后，再查：select * from ... where id = ()。
3. 这种设计，需要良好的设计能力，也需要一定的经验。因此，建议大家多练多想。

**问答**