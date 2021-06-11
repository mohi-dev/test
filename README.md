سلام وقت بخیر

توضیحات مختصری درباره مینی پروژه

ای پی آی شماره یک برای دادن دسترسی میباشد و بدون صدا کردن آن عملا امکان استفاده از بقیه ای پی آی ها وجود نخواهد داشت

مستندات : 

1. api/user/getAccess ----> body parameters : user_name (string) , password (string)

CREATE:

2. api/post/create ----> body parameters : data (json)

READ:

3. api/post/ -----> body parameters : per_page (int) , order_by (string) , sort_by (string) , time_created_min (int), time_created_max (int) , data (string), ALL OPTIONAL

UPDATE:

4. api/post/update/{id} -----> body parameters : data (json) OPTIONAL

DELETE:

5. api/post/delete/{id} -----> no body parameters

GET:

6. api/post/{id} ------> no body parameters

همه ای پی آی ها با تمامی متد ها قابل اجرا میباشند.

سپاس از توجه و همراهی شما 
