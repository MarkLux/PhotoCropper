# PHP 照片裁剪脚本

### 说明

一个简单php脚本，用来自动把图片压缩并裁剪出一寸照片

Made by Mark Lux

### 注意

使用了PHP的Imagick扩展

*注：本地是apt安装的php7.0，安装扩展只需要apt install php-imagick即可*

调用了微软的人脸识别端口

### 使用

使用前先定义config.php文件，修改输入和输出目录以及各种参数如API和xls文件名

三个目录的说明：INPUT_DIR为原始照片目录，COPY_DIR为中间产物（单张一寸/两寸）目录，PRODUCT_DIR为最终生成的版式图片目录。

根据情况请调整process.php中微调的参数

**生成一寸照片副本 :** 本地运行 getOneSizeCopy.php 输出文件到COPY_DIR,前缀为OneSizeCopy_+文件名

**生成一寸八张版式 :** 需要先生成一寸的副本，然后本地运行 getMoreOne.php 输出文件到PRODUCT_DIR,文件名和中间副本名一样

**生成两寸照片副本 :** 本地运行 getTwoSizeCopy.php 输出文件到COPY_DIR,前缀为TwoSizeCopy_+文件名

**生成两寸八张版式 :** 需要先生成两寸的副本，然后本地运行 getMoreTwo.php 输出文件到PRODUCT_DIR,文件名和中间副本名一样

**生成读者证版式 ：** 本地运行getReaderCopy.php  输出文件到COPY_DIR,前缀为ReaderCopy

**生成四六级报名格式 ：** 本地运行getCETCopy.php 输出文件到COPY_DIR,前缀为CETCopy


### 更新日志

**2016-09-06** 项目开始 解决API问题

**2016-09-07** 解决图像裁剪问题 解决批量处理 封装

**2016-09-08** 优化文件名处理 添加批量生成版式 再封装

**2016-09-12** 增加两种照片格式 增加从XLS文件读取导入照片信息
