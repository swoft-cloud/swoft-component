# swoft-bean
Swoft bean container

1. 如果类里面 使用了`@xx` 注解，且类上面必须要有注解
2. 配置bean定义，如果要覆盖`@Bean`定义信息，必须beanName相同，否则会生成两个不同的对象。