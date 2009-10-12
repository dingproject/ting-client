<?php

interface TingClientObjectRecommendationRequest
{
		const SEX_MALE = 'male';
		const SEX_FEMALE = 'female';
	
		function getIsbn();
		
		function setIsbn($isbn);
		
		function getNumResults();
		
		function setNumResults($numResults);
		
		function getSex();
		
		function setSex($sex);
	
		function getAge();
		
		function setAge($minAge, $maxAge);
		
		function getDate();
		
		function setDate($fromDate, $toDate);
		
}
