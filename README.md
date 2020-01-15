# IIST-Book-Grant-Final

Database Schema Information:
1.	tbl_lib_account: Stores the Student ID along with semester Id of the student and account activation details.
2.	tbl_lib_semester: Stores the semester details along with the start date and end date, amount set for book grant set by library staff. The semester Id is foreign key for the above table.
3.	tbl_lib_approved: This table stores all the books that are approved  by all the authorities.
4.	tbl_lib_bills: This table stores the bill information like bill no, amount, bill upload time and the bill location on the server.
5.	tbl_lib_books: This able stores information about the book like title ,author ,publisher ,faculty ,Student ID(user id). 
6.	tbl_lib_book_category: This table contains information for different book categories for different semester ID’s along with allowed balance.
7.	tbl_lib_book_conversion: category id 1 and 2 ie category 1 can be converted to category 2 along with the order sequence for multiple conversion and sem_id ie which book conversions valid for which sem.
8.	tbl_lib_book_conversion_record: Stores the book_id for which the book conversion took place and amount borrowed.
9.	tbl_lib_faculty_book: Stores the subject coverage and category set by faculty. It also stores the category change done by faculty. 
10.	tbl_lib_flag: This table stores the different approvals required for book grant. The user_id is the Id of the authority that changed the status of the book.  The remarks and status_of_approval are also present. The user_id until the request is pending. The role_id records the role of the authority.
11.	tbl_lib_order: This table includes the order of approval followed. The order is followed according to the order sequence and the status of approval. If provision for resubmit is NO then  resubmit button is not shown to that authority.
12.	tbl_lib_resubmit: This table has the old information of the book which was asked to resubmit.The new information sent by the student is updated in the tbl_lib_books and the old information is stored in this table.
13.	tbl_lib_resubmit_bills: This table includes the old bill_id of the previous bill. The new bill uploaded will have a new bill_id which is updated in the tbl-lib_books. 
14.	tbl_lib_semester: This table includes the different semesters. The sem_id of this table is used in other tables to check the semester of the student. The submission of book grant is checked with the sem start date and end date. If the date of book request does not lie in the start date and end date range the request is rejected automatically. All the semesters need to be added to the database. The library staff can just change the dates for semesters to enable book grant.
15.	tbl_lib_user_category: This table holds the information of the amount approved by library staff for each book along with the semesters. If the conversion_done attribute is YES the conversion record is searched for in tbl_lib_book_conversion_record.

Important Points:
1.	The Academic block needs to change the status of activation of account in tbl_lib_account of every student eligibile.
NA means Not Active and A means Active
2.	All semester needs to be present in the database beforehand. The library staff after the account activation is done will just change the semester start date and end date in tbl_lib_semester to enable book grant. If any one of the first two steps is not done the book grant request will not be uploaded on the database.
3.	While adding book grant request student has an option to borrow amount from different categories.
4.	The faculty chosen by the user is shown the book grant request uploaded by the user. Once the faculty adds subject coverage and category for the book and approves it The user needs to be conveyed by email to add bill.
5.	If faculty rejects or resubmits the message is conveyed to the student. incase of resubmit the changes can be done by the student by going in the resubmit section of the account.
6.	The bill upload details are added by the student and the bill is uploaded onto the database.
7.	The library staff receives the request.

Folders and files
1.	application folder: The application folder has all the jquery and bootstrap libraries.
2.	All the uploaded bills are stored in bills folder. The bills folder has a sub directory of the semester. Inside the semester folder there is student id folder. Inside each student id folder there are bill_no folders of the books submitted by the student.
3.	Js folder: This folder includes the js scripts for student, faculty, library staff.
3.1	Add-book.js: Uses ajax to call add-book.php which adds the books on the database by making server side validations.
3.2	faculty.js : fetches book categories available and stores them in array bookCategories on client side. Uses ajax to call view-request.php to get the respective request like pending, approved, rejected, resubmitted. This is used for faculty in faculty dashboard dashboard. The respective book details are fetched and saved in arr array. $.submit is used to run the faculty-book-status.php which adds the subject coverage and remarks and status of the respective book.
3.3	view-all.js: This is used in student login to display student book grants. The requests shown can be pending,rejected,resubmit,all according to the status chosen by the user. $.resubmit is to change the book details added in case of resubmit. $.REUpload is for reuploading of bill.
3.4	view-libst.js This is same like above just this one is for library staff. All the js for library staff is included here from displaying book requests to changing sem dates.
4.	php folder: This folder includes some common php files used by other files.
4.1	auto-add-next-flag.php: This file automatically adds the nest approval flag in tnht tbl_lib_flag table. Post should have Book_id, session should already be started use this file as include
4.2	bookcategories.php: This files sends all active book categories to client at faculty.js. In faculty.js each book gets only the categories that have the same sem_id as the category. 
4.3	change-category.php : This file inserts data in tbl_lib_faculty_book which maintains the subject coverage and category of a book. The number of times the book is resubmitted by the user as asked to resubmit by authority  this table will have that many subject coverage and category.
4.4	change-sem-dates.php : This file includes updating the sem start and end date in tbl_lib_semester by library staff.
4.5	faculty-book-status: Add  the subject coverage and category into tbl_lib_faculty_book table. It also adds the next flag if the book is approved. Note here there is a separate code for adding the next flag. The auto-add-next-flag is not used here.
4.6	get-balance.php: This file gets the balance for each book category of the student.
4.7	get-conversion-category.php:  This files gets the conversion category for the user to be displayed in add-book tab of dashboard. The user can choose any of these category if they want to borrow amount from other category.
4.8	get-faculty-for-resubmit.php: In case of resubmit the user might change the faculty for the book. This file fetched all the faculty members from tbl_user.
4.9	get-pending-user.php: This is used in view-libst.js to get the pending, approved, rejected, resubmit request as chosen by the staff.
4.10	get-semester.php: This fetches all the semesters. This is used to set the sem dates for each sems as this file fetches all the sems from the database.
4.11	mysqli.php: The oop sqli connection is made in this file with the database.
4.12	record-status.php: This file is used to record the status of the library staff for a particular book. This files adds the approved amount in tbl_lib_user_category and if any borrow from other categories took place it stores them in tbl_lib_book_conversion_record.
4.13	show-user-books.php: This files shows all the books of a particular student based on the status chosen by the library staff.
4.14	status-staff-update: This file was made for an earlier version. After the changes asked by Ancy Ma’am this file has now no use.
4.15	status-update-emp.php: Updates the status of the flag for authorities above library staff. It also uses auot-add-next-flag.php
4.16	view-request.php: This is used by the faculty to get the respective request.
5.	add-book.php: This file adds the book grant request with all book  details on the server.
6.	book-info.php: This php file shows an all the details of the a particular book. The book id should be passed in post for this file to work.
7.	dashboard.php: This file is the dashboard for the student.
8.	dashboard.php: This file is the dashboard for faculty.
9.	dashEmp.php: This is the dashboard for everyone above library staff.
10.	dashtry.php: This is the dashboard for the library staff.
11.	enlist-books.php: This file shows all the books received in array of $_POST[‘Books’]. This is used by the dashEmp’s view-emp.php file.
12.	faculty-list-menu: fetches all the faculty names and ids for a dropdown select menu. This file is included in add-book part of dashboard.php
13.	 generate-reports.php: This file is incomplete. This file is for generating pdf reports for the library.
14.	index.php: This Is the login screen of the book grant system.
15.	login.php: Once the user adds details in index.php after client side vertification server side verifications are done here and different authorities are navigated to different dashboards.
16.	multiupload.php: This file was for uploading multiple bills for library but after the changes asked by the library staff it uploads only  a single bill to the database. The accepted extension are jpg","jpeg","pdf","png.
17.	resubmit.php: Used in dashboard.php inc ase of resubmit of book details.
18.	resubmit-upload: For resubmitting new bills.
19.	 view-all.php: View request made by student. Used ins student dashboard.php
20.	 view-emp.php: To view requests from dashEmp.php
21.	view-request-staff.php:  To view requests from dashtry.php


Parts Left to be done:
1.	Add discuss button for everyone above library staff instead of reject.
2.	Generate reports and statistics.
3.	Clear session data on logout.
4.	Check on client side book grant request add date is between sem start and end date on client side. 
in dashboard.php (add checking code here)
 $("[href='#Add-Book']").click(function(){
5.	Instead of just updating sem start and end date for library staff make a provision for them to insert new semester in tbl_lib_semester.
6.	Create a procedure for admin block that changes the status of activation from NA to A in tbl_lib_account after eglibility criteria is met.
7.	Add indexes to speed up queries.






