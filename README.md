# lagou crawler

-----

### Analysis

#### Time

*Time : 2016-06-20 15:30:00*

#### Data Number

`SELECT count(uuid) AS number FROM  lagou;`

*Data Number : 3267*

#### District

`SELECT district, COUNT(district) AS number FROM lagou GROUP BY district ORDER BY number DESC;`

| District | Number | Proportion |
| :------: | :----: | :--------: |
|   朝阳区    |  1363  |   41.72%   |
|   海淀区    |  1088  |   33.3%    |
| unknown  |  301   |   9.21%    |
|   东城区    |  160   |    4.9%    |
|   丰台区    |  117   |   3.58%    |
|   西城区    |   92   |   2.82%    |
|   昌平区    |   66   |   2.02%    |
|   大兴区    |   30   |   0.92%    |
|   通州区    |   23   |    0.7%    |
|   石景山区   |   22   |   0.67%    |
|   顺义区    |   3    |   0.09%    |
|   房山区    |   2    |   0.06%    |

#### FinanceStage

`SELECT financeStage, COUNT(financeStage) AS number FROM lagou GROUP BY financeStage ORDER BY number DESC;`

| FinanceStage | Number | Proportion |
| :----------: | :----: | :--------: |
|   成长型(A轮)    |  600   |   18.37%   |
|   初创型(未融资)   |  588   |    18%     |
|     上市公司     |  479   |   14.66%   |
|   初创型(天使轮)   |  432   |   13.22%   |
|  成长型(不需要融资)  |  235   |   7.19%    |
|   成长型(B轮)    |  233   |   7.13%    |
|  成熟型(D轮及以上)  |  222   |    6.8%    |
|  初创型(不需要融资)  |  175   |   5.36%    |
|  成熟型(不需要融资)  |  166   |   5.08%    |
|   成熟型(C轮)    |  137   |   4.19%    |

#### Work Year

`SELECT workYear, COUNT(workYear) AS number FROM lagou GROUP BY workYear ORDER BY number DESC;`

| Work Year | Number | Proportion |
| :-------: | :----: | :--------: |
|   3-5年    |  1457  |   44.60%   |
|   1-3年    |  1356  |   41.51%   |
|    不限     |  234   |   07.16%   |
|   5-10年   |  162   |   04.96%   |
|   应届毕业生   |   30   |   0.92%    |
|   1年以下    |   26   |    0.8%    |
|   10年以上   |   2    |   0.06%    |


#### Education

`SELECT education, COUNT(education) AS number FROM lagou GROUP BY education ORDER BY number DESC;`

| Education | Number | Proportion |
| :-------: | :----: | :--------: |
|    本科     |  1797  |    55%     |
|    大专     |  1059  |   32.42%   |
|   学历不限    |  410   |   12.55%   |
|    硕士     |   1    |   0.03%    |

-----

### URL

[json](http://www.lagou.com/jobs/positionAjax.json?px=default&first=true&city=%E5%8C%97%E4%BA%AC&pn=1&kd=PHP)

[job](http://www.lagou.com/jobs/1866587.html)

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

`companyLabelList`      公司标签列表

`industryField`         行业领域

`financeStage`          融资阶段

------

`city`                  城市

`district`              区域

`businessZones`         商业区

`address`               具体地址

------

`salary`                薪水

`workYear`              工作经验

`education`             学历要求

`jobNature`             工作性质

`jobDescription`        职位描述

------

`createTime`            创建时间

`jobUrl`                招聘URL

`collectionTime`        采集时间

------

### Query String Parameters

```
first:true

needAddtionalResult:false

//排序方式
px:new

//搜索的职位
kd:php

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

//页码
pn:1
```
