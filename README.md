# airAnime
airAnime 是一款聚合「番剧搜索」工具，也许你会喜欢。

- [airAnime v3](https://air.tls.moe)

## 部署
### 手动
本项目的核心代码不依赖前端构建工具，因此只需做以下几步即可。

- 准备项目的 `./index.html` 与 `./vapp/` 文件夹
- 使用 Web 服务器（NGINX、Apache 等）绑定至相应目录
- 最后，请确保 `.mjs` 文件响应头中 `Content-Type` 字段为 `application/javascript`

需要注意的是，部分数据源基于后端的抓取脚本。若手动部署，请务必查看 [airAnime-rp](./api/) 文档。

### Vercel 托管
或者，可以点击下方按钮以直接部署至 [Vercel](https://vercel.com/) 平台，无需其余操作。

[![Deploy to Vercel](https://vercel.com/button)](https://vercel.com/import/project?template=https://github.com/txperl/airAnime/tree/master/)

## 最后
### 一些话
> 因为感觉经常在各视频网站找番有点浪费时间，于是就突然萌生了一个想法：如果能同时进行，就不会浪费太多时间了。所以，airAnime 就出于这个想法诞生了。

上面的这段话来自最初的 `v1` 版本，那也是我的第一个开源项目。经历了 `v1` 与 `v2` 版本后，已经有很长一段时间没有维护了。

直至近期，不知为何突然有一种冲动，想要将它继续下去。为此，`v3` 版本就这样诞生了。

我想这也许是一种希望，希望自己不要忘记最初的那种不期许什么，但又能无比快乐地做着某些事情的感觉。

也希望你们能够使用愉快。

; )
