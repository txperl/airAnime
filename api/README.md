# airAnime-rp
由于浏览器限制，仅通过前端进行数据抓取会遇到 [CORS](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS) 问题。

因此，本项目提供了基于 [Cloudflare Workders](https://workers.cloudflare.com/) 和 [Vercel Serverless Functions](https://vercel.com/docs/concepts/functions/serverless-functions) 的后端数据抓取脚本。使用这些脚本不需要拥有自己的服务器，并且使用起来十分简单。

**若您在部署 airAnime 时为手动部署**，请务必完成以下步骤。

## 使用
### 1. 部署 rp 脚本
#### Cloudflare Workder
- [main.js](./cloudflare_workers/main.js)

#### Vercel Serverless Function
[![Deploy to Vercel](https://vercel.com/button)](https://vercel.com/import/project?template=https://github.com/txperl/airAnime/tree/master/api/vercel_serverless_functions/)

默认的 API 路由为 `https://{your_domain_name}/fetch`。

### 2. 更改配置
最后，将 `./vapp/source/all.mjs` 中的 `AIRANIME_RP_URL` 变量修改为相应的服务地址即可，如 `https://airanime-rp.kaka.run/fetch`。