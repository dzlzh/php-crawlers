# lagou crawler

-----

### URL

[json](http://www.lagou.com/jobs/positionAjax.json?px=default&first=true&city=%E5%8C%97%E4%BA%AC&pn=1&kd=PHP)


------

### Data

`uuid`

`positionId`            职位ID

`positionName`          职位名称

`positionType`          职位类型

`positionAdvantage`     职位诱惑

------

`companyName`           公司名称

`companyShortName`      公司简称

`companySize`           公司规模

`companyHome`           公司主页

`industryField`         行业领域

`financeStage`          融资阶段

------

`city`                  城市

`district`              区域

`businessZone`          商业区

`address`               具体地址

------

`salary`                薪水

`workYear`              工作经验

`education`             学历要求

`jobNature`             工作性质

`jobDescription`        职位描述

------

`createTime`            创建时间

`collectionTime`        采集时间


------

### Query String Parameters

```
first:true

needAddtionalResult:false

//页码
pn:1

//搜索的职位
kd:php

//排序方式
px:new

//工作地点
city:北京

//行政区
district:朝阳区

//商区
bizArea:望京

//工作经验
gj:3年及以下

//学历要求
xl:大专

//融资阶段
jd:未融资

//行业领域
hy:移动互联网

//月薪
yx:10k-15k

//工作性质
gx:全职
```
