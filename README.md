# OD mission 4
- 註：這只是初步設計，還未考量到 server 內部的 thread 分配

## 目前 Database 的 table
1. login: 紀錄使用者資訊
![Screenshot from 2022-05-14 21-21-28](https://user-images.githubusercontent.com/34702573/168427711-51f48786-4093-4a0a-9f93-85a65e4d3259.png)

2. directory: 紀錄每個資料夾內 metadata 的 id（id 最終該怎麼存還待任務一確定）
附註：下圖的 documentID 是手動加上去的，目前程式還無法編輯此部份
![Screenshot from 2022-05-14 21-23-51](https://user-images.githubusercontent.com/34702573/168427714-38f8249f-4155-4235-9d30-b0c370597f79.png)

3. serverVar: 紀錄 server 自己的變數，目前只紀錄了所有資料夾的數量，作為下個新資料夾的編號
附註：亦即儲存 directoryID 的最大值 + 1
![Screenshot from 2022-05-14 21-24-03](https://user-images.githubusercontent.com/34702573/168427716-75cae918-c932-4b48-9f88-3fd6221d347b.png)

## 登入機制
- 跟登入有關的檔案都在 registration/ 裡面（除了 index.php 是在外面）
- 登入流程：

![login](https://user-images.githubusercontent.com/34702573/168427490-101a314c-4a0e-4230-a60e-4f5108f04543.png)


