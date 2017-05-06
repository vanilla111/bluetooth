
# 逼乎API文档

~~(PinkD ver)~~

### 规则

1. 所有接口POST，参数 x-www-form-urlencoded模式，返回:json。

2. 状态码：
 - 200 —— 成功
 - 400 —— 参数错误
 - 401 —— 用户认证错误
 - 500 —— 奇怪的错误

3. 登录后用token代表用户。


### 1. 注册

地址：https://api.caoyue.com.cn/bihu/register.php    
参数：

- username
- password

返回:

```json
{
  "status": 200,
  "info": "success",
  "data": {
    "id": 1,
    "username": "admin",
    "password": "admin",
    "avatar": null,
    "token": "00309c36f7166d1c4c045682e7f949cb9c9edc9a"
  }
}
```

### 2. 登录

地址：https://api.caoyue.com.cn/bihu/login.php    
参数

- username
- password

返回:：

```json
{
  "status": 200,
  "info": "success",
  "data": {
    "id": 1,
    "username": "admin",
    "avatar": null,
    "token": "6c5f989bdc56fe25f8a2b08443f354c910280c50"
  }
}
```

返回:用户信息及token。

### 3. 修改头像

地址：https://api.caoyue.com.cn/bihu/modifyAvatar.php    
参数

- token
- avatar

传用户头像地址。本API不负责图片文件储存，图片储存请右转阿里，七牛，然后把图片地址传上来。

返回:

```json
{
  "status": 200,
  "info": "success"
}
```

### 4. 修改密码

地址：https://api.caoyue.com.cn/bihu/changePassword.php    
参数

- token
- password

传用户头像地址。本API不负责图片文件储存，图片储存请右转[阿里](https://help.aliyun.com/product/31815.html)，[七牛](http://developer.qiniu.com/article/index.html#kodo-api-handbook)，然后把图片地址传上来。

返回:    

```json
{
  "status": 200,
  "info": "success",
  "data": {
    "token": "xxx"
  }
}
```

### 4. 取问题列表

地址：https://api.caoyue.com.cn/bihu/getQuestionList.php
参数

- token
- page
- count:可空，每页条数，默认20条。

返回:

```json
{
  "status": 200,
  "info": "success",
  "data": {
    "totalCount": 3,
    "totalPage": 1,
    "questions": [
      {
        "id": 1,
        "title": "哦哈哟",
        "content": "起床啦",
        "date": "2016-12-15 12:16:09",
        "recent": "2016-12-15 12:17:10",
        "answerCount": 0,
        "authorId": 1,
        "exciting": 2,
        "naive": 0,
        "authorName": "admin",
        "authorAvatar": "https://avatars1.githubusercontent.com/u/14852537?v=3&s=460",
        "images": [
                  {
                    "url": "https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo_top_ca79a146.png"
                  },
                  {
                    "url": "http://cdn-qn0.jianshu.io/assets/web/logo-58fd04f6f0de908401aa561cda6a0688.png"
                  }
                ],
         "is_exciting": false,
         "is_naive": false,
         "is_favorite": false
      },
      {
        "id": 2,
        "title": "test",
        "content": "testquestion",
        "date": "2016-12-15 11:53:09",
        "recent": null,
        "answerCount": 0,
        "authorId": 1,
        "exciting": 0,
        "naive": 0,
        "authorName": "admin",
        "authorAvatar": "https://avatars1.githubusercontent.com/u/14852537?v=3&s=460" ,
        "images": [],
        "is_exciting": false,
        "is_naive": false,
        "is_favorite": false
         },
      {
        "id": 1,
        "title": "test",
        "content": "test",
        "date": "2016-12-15 11:48:33",
        "recent": null,
        "answerCount": 0,
        "authorId": 1,
        "exciting": 0,
        "naive": 0,
        "authorName": "admin",
        "authorAvatar": "https://avatars1.githubusercontent.com/u/14852537?v=3&s=460",
        "images": [],
        "is_exciting": false,
        "is_naive": false,
        "is_favorite": false
      }
    ],
    "curPage": 0
  }
}
```

recent表示最近回复时间，没有回复时为null。

### 5. 取回答列表

地址：https://api.caoyue.com.cn/bihu/getAnswerList.php    
参数：

- token
- page
- qid
- count:可空，每页条数，默认20条

返回:：

```json
{
  "status": 200,  
  "info": "success",
  "data": {
    "totalCount": 1,
    "totalPage": 1,
    "answers": [
      {
        "id": 1,
        "content": "早啊，单身狗",
        "date": "2016-12-15 12:17:10",
        "exciting": 0,
        "naive": 0,
        "best": 1,
        "authorId": 2,
        "authorName": "test",
        "authorAvatar": null,
        "images": [],
        "is_exciting": false,
        "is_naive": false
      }
    ],
    "curPage": 0
  }
}
```

### 6. 发布问题

地址：https://api.caoyue.com.cn/bihu/question.php    
参数：

- title
- content
- token

返回:：

```json
{
  "status": 200,
  "info": "success"
}
```

### 7. 发布回答

地址：https://api.caoyue.com.cn/bihu/answer.php    
参数：

- qid
- content
- token

返回:：

```json
{
  "status": 200,
  "info": "success"
}
```

### 8.收藏

地址：https://api.caoyue.com.cn/bihu/favorite.php    
参数：

- qid  
- token

返回:

```json
{
  "status": 200,
  "info": "success"
}
```

### 9.取消收藏

地址：https://api.caoyue.com.cn/bihu/cancelFavorite.php    
参数：

- qid
- token

返回:：

```json
{
  "status": 200,
  "info": "success"
}
```

### 10.收藏列表

地址：https://api.caoyue.com.cn/bihu/getFavoriteList.php    
参数：

- token
- page
- count:可空，每页条数，默认20条

返回参考前面问题列表

### 11.采纳

地址：https://api.caoyue.com.cn/bihu/accept.php    
参数：

- qid
- aid -> answer id
- token

返回:：

```json
{
  "status": 200,
  "info": "success"
}
```

### 12.![](http://www.webpagefx.com/tools/emoji-cheat-sheet/graphics/emojis/+1.png)

地址：https://api.caoyue.com.cn/bihu/exciting.php    
参数：

- id
- type: ANSWER -> 2, QUESTION -> 1
- token

返回:：

```json
{
  "status": 200,
  "info": "excited"
}
```

### 13. ![](http://www.webpagefx.com/tools/emoji-cheat-sheet/graphics/emojis/-1.png)

地址：https://api.caoyue.com.cn/bihu/naive.php    
参数：

- id
- type: ANSWER -> 2, QUESTION -> 1
- token

返回:：

```json
{
  "status": 200,
  "info": "naive"
}
```

### 14.取消![](http://www.webpagefx.com/tools/emoji-cheat-sheet/graphics/emojis/+1.png)

地址：https://api.caoyue.com.cn/bihu/cancelExciting.php    
参数：

- id
- type: ANSWER -> 2, QUESTION -> 1
- token

返回:：

```json
{
  "status": 200,
  "info": "excited"
}
```

### 15.取消![](http://www.webpagefx.com/tools/emoji-cheat-sheet/graphics/emojis/-1.png)

地址：https://api.caoyue.com.cn/bihu/cancelNaive.php    
参数：

- id:  
- type: ANSWER -> 2, QUESTION -> 1  
- token

返回:

```json
{
  "status": 200,
  "info": "naive"
}
```

### 16.上传图片

地址：https://api.caoyue.com.cn/bihu/newImage.php    
参数：

- id
- type: ANSWER -> 2, QUESTION -> 1
- url

返回:

```json
{
  "status": 200,
  "info": "success"
}
```

### 17.获取图片

地址：https://api.caoyue.com.cn/bihu/getImage.php    
参数：

- id
- type: ANSWER -> 2, QUESTION -> 1

返回:

```json
{
  "status": 200,
  "info": "success",
  "data": [
    {
      "url": "www.baidu.com"
    }
  ]
}
```
