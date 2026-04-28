<div align="center">
  <img width="96" src="./public/favicon.ico" alt="Lsky Pro Logo" />
  <h1>Lsky Pro — 美化二开版</h1>
  <p>
    基于 <a href="https://github.com/lsky-org/lsky-pro">Lsky Pro</a> 深度 UI 美化与功能增强的二次开发版本，可独立部署运行。
  </p>
</div>

<div align="center">

[![PHP](https://img.shields.io/badge/PHP-%3E%3D8.0-777bb4?logo=php)](http://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-9.x-ff2d20?logo=laravel)](https://laravel.com)
[![License](https://img.shields.io/badge/License-GPL%20v3-yellowgreen)](https://github.com/lsky-org/lsky-pro/blob/master/LICENSE)
[![Docker](https://img.shields.io/badge/Docker-willowgod%2Flsky--liushen-2496ed?logo=docker)](https://hub.docker.com/r/willowgod/lsky-liushen)
[![Original](https://img.shields.io/badge/上游-lsky--org%2Flsky--pro-8b5cf6)](https://github.com/lsky-org/lsky-pro)

</div>

---

> [!IMPORTANT]
> 本项目基于 [lsky-org/lsky-pro](https://github.com/lsky-org/lsky-pro) 二次开发，所有核心功能归属原作者。如有侵权，请联系删除。

---

## 目录

- [新增特性](#新增特性)
- [快速开始](#快速开始)
- [手动部署](#手动部署)
- [Docker 部署](#docker-部署)
- [从原版升级](#从原版升级)
- [原项目功能](#原项目功能)
- [技术栈](#技术栈)
- [鸣谢](#鸣谢)
- [开源许可](#开源许可)

---

## 新增特性

本二开版本在保留原项目全部功能的基础上，新增以下特性：

### 界面与体验

| 特性 | 说明 |
|------|------|
| **亮暗色主题切换** | 支持亮色 / 暗色双模式，跟随系统偏好，一键切换，基于 CSS 变量实现无闪烁过渡 |
| **全局 UI 美化** | 重新设计全部页面：圆角卡片、渐变按钮、毛玻璃顶栏、平滑过渡动画、现代阴影系统 |
| **中文字体优化** | 引入「官甲清香」字体，优化中文排版与阅读体验 |
| **响应式侧边栏** | 移动端友好的侧边栏导航，支持手势关闭 |
| **浮动主题按钮** | 右下角主题切换悬浮按钮，带有 hover 动效 |

### API 与权限

| 特性 | 说明 |
|------|------|
| **精细化 Token 权限** | 创建 API 密钥时可指定权限范围（图片/相册/管理 三组 7 项细粒度权限） |
| **Web 端密钥管理** | 可视化密钥管理界面：创建、查看、重命名、删除，支持密码二次验证 |
| **权限开关联动** | 按组全选/反选，子项联动，一键切换全部权限 |
| **向后兼容** | 旧版 Token（无限权字段）自动视为全部权限，不影响存量业务 |

### 构建与部署

| 特性 | 说明 |
|------|------|
| **官方 Docker 镜像** | 提供预构建镜像 [`willowgod/lsky-liushen:latest`](https://hub.docker.com/r/willowgod/lsky-liushen)，开箱即用 |
| **覆盖式升级** | 可直接覆盖原项目文件升级，保留 `.env`、`storage/`、上传数据，无缝切换 |
| **独立部署** | 包含完整 Laravel 项目代码，可作为全新站点独立安装 |

---

## 快速开始

### 前置要求

- PHP >= 8.0.2
- MySQL 5.7+ / PostgreSQL 9.6+ / SQLite 3.8.8+
- BCMath, Ctype, DOM, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, Imagick 扩展
- `exec`, `shell_exec`, `readlink`, `symlink`, `putenv`, `getenv`, `chmod`, `chown`, `fileperms` 函数可用

### 部署方式

推荐使用 Docker 快速部署（见下方），或手动部署（与原项目流程完全一致）。

---

## 手动部署

> 适用于宝塔面板、LNMP、虚拟主机等传统环境。

```bash
# 1. 克隆仓库
git clone https://github.com/willow-god/LSKY-Pro-LiuShen.git
cd LSKY-Pro-LiuShen

# 2. 安装依赖
composer install --no-dev

# 3. 配置环境
cp .env.example .env
php artisan key:generate
# 编辑 .env，填入数据库、邮件、存储等信息

# 4. 初始化数据库
php artisan migrate --seed

# 5. 设置权限 (Linux)
chmod -R 775 storage bootstrap/cache

# 6. 配置 Web 服务器将根目录指向 public/
# 7. 访问首页，按安装向导完成初始化
```

---

## Docker 部署

官方镜像开箱即用，与原版 Lsky Pro 镜像使用方式一致：

```bash
# 拉取镜像
docker pull willowgod/lsky-liushen:latest

# 启动容器
docker run -d \
  --name lsky \
  -p 8080:80 \
  -v $(pwd)/data/html:/var/www/html \
  willowgod/lsky-liushen:latest
```

或使用 Docker Compose：

```yaml
services:
  lsky:
    image: willowgod/lsky-liushen:latest
    container_name: lsky
    ports:
      - "8080:80"
    volumes:
      - ./data/html:/var/www/html
    restart: unless-stopped
```

> [!TIP]
> 将仓库代码放入 `./data/html` 目录，或先在宿主机完成 `.env` 配置与数据库初始化后启动容器。镜像内置了所有美化与功能增强，首次访问按引导完成数据库配置即可。

---

## 从原版升级

如果你已有正在运行的 Lsky Pro 实例，可直接覆盖升级：

> [!WARNING]
> **覆盖前务必备份！** 备份整个项目目录。

1. **备份原项目**：将现有部署目录完整压缩备份
2. **下载本仓库代码**，解压得到全部文件
3. **覆盖替换**：将本仓库所有文件复制粘贴到原项目目录，直接覆盖同名文件
4. **以下文件和目录不要删除**（运行时自动生成）：
   - `.env` — 环境配置
   - `storage/` — 日志、缓存、上传文件
   - `bootstrap/cache/` — 框架缓存
   - `public/storage` — 存储软链接
5. **刷新缓存**（如遇异常可执行）：
   ```bash
   php artisan config:clear
   php artisan view:clear
   php artisan cache:clear
   ```

> Docker 用户同理，将仓库文件覆盖到宿主机的挂载目录，然后 `docker compose restart`。

---

## 原项目功能

> 以下功能继承自原项目 [lsky-org/lsky-pro](https://github.com/lsky-org/lsky-pro)，本二开版本完整保留。

- **多存储支持**：本地、AWS S3、阿里云 OSS、腾讯云 COS、七牛云、又拍云、FTP、SFTP、WebDav、Minio
- **多数据库支持**：MySQL 5.7+、PostgreSQL 9.6+、SQLite 3.8.8+、SQL Server 2017+
- **多缓存驱动**：Memcached、Redis、DynamoDB 等，默认文件缓存
- **上传功能**：多图上传、拖拽上传、粘贴上传、动态策略上传、一键复制链接
- **图片管理**：瀑布流展示、右键操作、单选多选、重命名、批量操作
- **角色组系统**：为每个组配置多个存储策略，设置上传规则、频率限制、图片审核
- **水印系统**：图片水印、文字水印、水印平铺、位置/偏移/旋转角度可配
- **API 接口**：通过接口上传、管理图片和相册
- **在线更新**：支持增量更新、跨版本更新
- **图片广场**：公开图片展示

---

## 技术栈

| 类型 | 技术 |
|------|------|
| 后端框架 | Laravel 9.x |
| 前端框架 | Alpine.js + jQuery |
| CSS 方案 | Tailwind CSS 3 + Less |
| 构建工具 | Laravel Mix |
| 图标库 | Font Awesome 5 |
| 图表库 | ECharts 5 |
| 图片查看 | Viewer.js |
| 右键菜单 | 自研 context-js |
| 认证 | Laravel Sanctum (Bearer Token) |

---

## 鸣谢

- [lsky-org/lsky-pro](https://github.com/lsky-org/lsky-pro) — 原项目，本二开版本的基础
- [Laravel](https://laravel.com) — PHP 框架
- [Tailwind CSS](https://tailwindcss.com) — CSS 工具集
- [Alpine.js](https://alpinejs.dev) — 轻量前端框架
- [Font Awesome](https://fontawesome.com) — 图标库
- [ECharts](https://echarts.apache.org) — 数据图表
- [Intervention Image](https://github.com/Intervention/image) — 图片处理
- [league/flysystem](https://flysystem.thephpleague.com) — 文件系统抽象
- [Viewer.js](https://github.com/fengyuanchen/viewerjs) — 图片预览
- [官甲清香字体](https://github.com/willow-god/Sharding-fonts) — 项目中文字体

---

## 开源许可

本项目继承原项目的 [GPL v3](https://opensource.org/licenses/GPL-3.0) 开源协议。

Copyright (c) 2018-present Lsky Pro & 二开贡献者。

> 如本仓库内容涉及侵权，请通过 Issue 联系，将第一时间处理。
