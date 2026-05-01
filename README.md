<div align="center">
  <img width="96" src="./public/favicon.ico" alt="Lsky Pro Logo" />
  <h1>Lsky Pro — 美化增强版</h1>
  <p>一个基于 Lsky Pro 的美化与功能增强版本，包含现代化界面、暗色模式、OAuth 登录/绑定、自定义 CSS / JS，以及更完善的 Token 管理能力。</p>
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

- [核心亮点](#核心亮点)
- [新增特性](#新增特性)
- [快速开始](#快速开始)
- [手动部署](#手动部署)
- [Docker 部署](#docker-部署)
- [从原版升级](#从原版升级)
- [升级后执行迁移](#升级后执行迁移)
- [原项目功能](#原项目功能)
- [技术栈](#技术栈)
- [鸣谢](#鸣谢)
- [开源许可](#开源许可)

---

## 核心亮点

- **现代化界面与暗色模式**：统一重做后台、用户端、欢迎页、表格、弹窗与交互细节。
- **更完善的 Token 管理**：支持 Web 端创建、查看、重命名、删除，并提供二次密码确认。
- **精细化权限控制**：支持按能力分配 Token 权限，适合多客户端与多场景接入。
- **后台自定义 CSS / JS**：无需改源码即可做品牌化、统计接入和前端增强。
- **OAuth 登录 / 绑定**：支持第三方账号登录、账号绑定、按需开放注册与 PKCE。
- **兼容覆盖升级**：可直接替换原版部署，升级后执行 `php artisan migrate` 即可补齐新表结构。

---

## 新增特性

以下按模块详细说明本版本相对原版的增强内容：

### 1. 页面美化与使用体验升级

| 特性 | 说明 |
|------|------|
| **整体 UI 重构美化** | 对后台、用户端与欢迎页进行了系统化视觉调整，包括卡片、按钮、表格、弹窗、导航、阴影、圆角与动效 |
| **亮 / 暗双主题** | 支持亮色 / 暗色模式切换，并对主要页面做了专门适配 |
| **暗夜模式细节优化** | 重点处理上传区、仪表盘卡片、表格 hover、设置页、欢迎页等区域 |
| **浮动主题切换按钮** | 右下角悬浮切换，移动端与桌面端都更方便 |
| **统一交互反馈** | 多处按钮、卡片、表格、下拉菜单增加更一致的 hover / focus / transition 表现 |

### 2. 密钥管理与权限管理增强

| 特性 | 说明 |
|------|------|
| **Web 端密钥管理** | 可视化管理 API 密钥，支持创建、查看、重命名、删除 |
| **密码二次验证** | 创建密钥时需再次输入密码确认身份 |
| **精细化 Token 权限** | 支持按能力分配 Token 权限，适用于上传、读取、删除、相册等场景 |
| **权限分组与联动选择** | 支持按组全选 / 取消、子项联动，配置更直观 |
| **兼容旧 Token** | 对旧版全权限 Token 保持兼容 |

### 3. 自定义 CSS / JS 扩展能力

| 特性 | 说明 |
|------|------|
| **后台直接配置** | 可在后台设置页直接填写自定义 CSS 与自定义 JavaScript |
| **适合品牌化与统计接入** | 可用于品牌样式覆盖、统计脚本、前端行为增强与细节微调 |
| **CSS 本地缓存优化** | 自定义 CSS 支持基于内容更新的本地缓存策略 |
| **JS 保持简单可控** | 自定义 JS 仍按页面脚本末尾原样注入 |

### 4. 构建与部署便利性

| 特性 | 说明 |
|------|------|
| **官方 Docker 镜像** | 提供预构建镜像 [`willowgod/lsky-liushen:latest`](https://hub.docker.com/r/willowgod/lsky-liushen)，开箱即用 |
| **覆盖式升级** | 可直接覆盖原项目文件升级，保留 `.env`、`storage/`、上传数据 |
| **独立部署** | 包含完整 Laravel 项目代码，可作为全新站点独立安装 |

### 5. OAuth 登录与账号绑定

| 特性 | 说明 |
|------|------|
| **后台可配置 OAuth 提供方** | 可填写客户端 ID、密钥、授权地址、Token 地址、用户信息地址等参数 |
| **支持用户绑定第三方账号** | 登录后可在设置页绑定或解绑 OAuth 账号 |
| **支持按需开放注册** | 可控制 OAuth 登录后是否允许自动注册新用户 |
| **支持 PKCE** | 默认支持 PKCE，提高兼容性 |
| **升级后需执行迁移** | 从旧版本覆盖升级时，需执行数据库迁移创建相关数据表 |

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
5. **执行数据库迁移**（覆盖升级建议执行，尤其是需要启用 OAuth 时）：
   ```bash
   php artisan migrate
   ```
6. **刷新缓存**（如遇异常可执行）：
   ```bash
   php artisan config:clear
   php artisan view:clear
   php artisan cache:clear
   ```

> Docker 用户同理，将仓库文件覆盖到宿主机的挂载目录，然后 `docker compose restart`。

---

## 升级后执行迁移

如果你是从旧版本直接替换升级，建议在覆盖完成后执行一次数据库迁移：

```bash
php artisan migrate
```

这一步会补齐新版本需要的数据库结构；如果你准备使用 OAuth 登录 / 账号绑定功能，这一步通常是必须的。

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

---

## 开源许可

本项目继承原项目的 [GPL v3](https://opensource.org/licenses/GPL-3.0) 开源协议。

Copyright (c) 2018-present Lsky Pro & 二开贡献者。

> 如本仓库内容涉及侵权，请通过 Issue 联系，将第一时间处理。
