<img align="right" width="100" src="./public/favicon.ico" alt="Lsky Pro Logo"/>

<h1 align="left">Lsky Pro — 美化二开版</h1>

☁ 基于 <a href="https://github.com/lsky-org/lsky-pro">Lsky Pro</a> 的 UI 美化二次开发版本，同时可作为独立项目单独部署运行。

[![PHP](https://img.shields.io/badge/PHP->=8.0-orange.svg)](http://php.net)
[![License](https://img.shields.io/badge/license-GPL_V3.0-yellowgreen.svg)](https://github.com/lsky-org/lsky-pro/blob/master/LICENSE)
[![Original Project](https://img.shields.io/badge/原项目-lsky--org%2Flsky--pro-blue)](https://github.com/lsky-org/lsky-pro)

> [!IMPORTANT]
> **本项目基于原项目 [lsky-org/lsky-pro](https://github.com/lsky-org/lsky-pro) 进行 UI 美化二次开发，所有核心功能归属原作者所有。如有侵权，请联系本人删除。**

> [!WARNING]
> **在进行任何替换操作前，请务必备份原有文件！备份！备份！重要的事情说三遍！**

---

## 📖 相关教程

详细的美化说明与部署教程，请参阅博客文章：

👉 **[https://blog.liushen.fun/posts/eabede01/](https://blog.liushen.fun/posts/eabede01/)**

---

## 📌 项目说明

本仓库是对 Lsky Pro 开源图床项目的 UI 层美化二次开发版本，主要对前端界面进行了视觉优化与体验改进，后端核心逻辑与原项目保持一致。

- **原项目地址**：[https://github.com/lsky-org/lsky-pro](https://github.com/lsky-org/lsky-pro)
- **本项目定位**：UI 美化 + 二次开发，**也可直接作为独立项目部署运行**
- **Docker 镜像**：暂未打包 Docker 镜像，如有需要请自行构建

> 原开源版本已停止维护，不再进行新特性更新和 bug 修复。本二开版本同样基于该冻结版本，**不承诺跟进上游更新**，请知悉。

---

## 🚀 使用方式（覆盖替换）

### ⚠️ 替换前必读

> **请先备份！请先备份！请先备份！重要的事情说三遍！**
>
> 在覆盖前，请完整备份你的原始项目目录，以防操作失误导致无法回滚。

### 方式一：传统宝塔 / 手动部署

1. **备份原项目**：将现有部署目录完整压缩备份至安全位置。
2. **下载本仓库**：将本仓库代码下载并解压到本地。
3. **覆盖文件**：将本仓库的所有文件**直接复制粘贴**到原有部署目录，**让其自动覆盖同名文件**。
   - ✅ 推荐做法：直接复制 → 粘贴 → 确认覆盖
   - ❌ 错误做法：先删除原目录中的所有文件，再复制新文件（这会导致运行时自动生成的文件丢失，造成项目异常）
4. **注意事项**：以下目录/文件为运行时自动生成，**不要手动删除**：
   - `storage/` — 日志、缓存、上传文件等
   - `bootstrap/cache/` — 框架缓存
   - `.env` — 环境配置文件
   - `public/storage` — 软链接
5. **清除缓存**（可选，如果出问题可以尝试）：替换完成后执行以下命令刷新框架缓存：
   ```bash
   php artisan config:clear
   php artisan view:clear
   php artisan cache:clear
   ```

### 方式二：Docker 部署

如果你使用 Docker 部署，通常项目目录会通过卷挂载（volume）映射到宿主机，操作步骤如下：

1. **找到挂载目录**：查看你的 `docker-compose.yml` 或 `docker run` 命令，确认项目代码挂载在宿主机的哪个路径。
2. **备份挂载目录**：备份该宿主机路径下的所有内容。
3. **覆盖文件**：将本仓库文件复制到挂载目录，**直接覆盖，不要先清空目录**。
4. **重启容器**（部分情况需要）：
   ```bash
   docker compose restart
   # 或
   docker restart <容器名>
   ```
5. **清除缓存**（进入容器内执行，出问题后再尝试）：
   ```bash
   docker exec -it <容器名> php artisan config:clear
   docker exec -it <容器名> php artisan view:clear
   docker exec -it <容器名> php artisan cache:clear
   ```

> 由于目前**未提供独立的 Docker 镜像**，如需 Docker 部署本项目，请基于原项目 Docker 镜像，将代码目录替换为本仓库内容，或自行编写 `Dockerfile` 构建镜像。

### 方式三：作为独立项目全新部署

本项目完整保留了 Lsky Pro 的所有功能，可脱离原项目**独立部署运行**，步骤与原项目安装流程一致：

1. 克隆或下载本仓库代码。
2. 安装 PHP 依赖：
   ```bash
   composer install --no-dev
   ```
3. 复制并配置环境文件：
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. 配置 `.env` 中的数据库、邮件、存储等信息。
5. 执行数据库迁移与初始化：
   ```bash
   php artisan migrate --seed
   ```
6. 设置目录权限（Linux）：
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```
7. 配置 Web 服务器（Nginx/Apache）将根目录指向 `public/`。
8. 访问安装向导完成初始化配置。

---

## 📌 功能列表（继承自原项目）

* [x] 支持 `本地` 等多种第三方云储存：`AWS S3`、`阿里云 OSS`、`腾讯云 COS`、`七牛云`、`又拍云`、`SFTP`、`FTP`、`WebDav`、`Minio`
* [x] 多种数据库驱动支持：`MySQL 5.7+`、`PostgreSQL 9.6+`、`SQLite 3.8.8+`、`SQL Server 2017+`
* [x] 支持配置使用多种缓存驱动：`Memcached`、`Redis`、`DynamoDB` 等，默认以文件方式缓存
* [x] 多图上传、拖拽上传、粘贴上传、动态设置策略上传、复制、一键复制链接
* [x] 强大的图片管理功能，瀑布流展示，支持鼠标右键、单选多选、重命名等操作
* [x] 自由度极高的角色组配置，可为每个组配置多个储存策略，储存策略可配置多个角色组
* [x] 可针对角色组设置上传文件、文件夹路径命名规则、上传频率限制、图片审核等功能
* [x] 支持图片水印、文字水印、水印平铺、设置水印位置、X/Y 轴偏移量、旋转角度等
* [x] 支持通过接口上传、管理图片、管理相册
* [x] 支持在线增量更新、跨版本更新
* [x] 图片广场

---

## 🛠 安装要求

- PHP >= 8.0.2
- BCMath PHP 扩展
- Ctype PHP 扩展
- DOM PHP 拓展
- Fileinfo PHP 扩展
- JSON PHP 扩展
- Mbstring PHP 扩展
- OpenSSL PHP 扩展
- PDO PHP 扩展
- Tokenizer PHP 扩展
- XML PHP 扩展
- Imagick 拓展
- `exec`、`shell_exec` 函数
- `readlink`、`symlink` 函数
- `putenv`、`getenv` 函数
- `chmod`、`chown`、`fileperms` 函数

---

## 😋 鸣谢

- [lsky-org/lsky-pro](https://github.com/lsky-org/lsky-pro) — 原项目，本二开版本基础
- [Laravel](https://laravel.com)
- [Tailwindcss](https://tailwindcss.com)
- [Fontawesome](https://fontawesome.com)
- [Echarts](https://echarts.apache.org)
- [Intervention/image](https://github.com/Intervention/image)
- [league/flysystem](https://flysystem.thephpleague.com)
- [overtrue](https://github.com/overtrue)
- [Jquery](https://jquery.com)
- [jQuery-File-Upload](https://github.com/blueimp/jQuery-File-Upload)
- [Alpinejs](https://alpinejs.dev/)
- [Viewer.js](https://github.com/fengyuanchen/viewerjs)
- [DragSelect](https://github.com/ThibaultJanBeyer/DragSelect)
- [Justified-Gallery](https://github.com/miromannino/Justified-Gallery)
- [Clipboard.js](https://github.com/zenorocha/clipboard.js)

---

## 📃 开源许可

本项目继承原项目开源协议：[GPL 3.0](https://opensource.org/licenses/GPL-3.0)

Copyright (c) 2018-present Lsky Pro & 二开贡献者。

> 如本仓库内容涉及侵权，请通过 Issue 或联系方式告知，将第一时间处理。

