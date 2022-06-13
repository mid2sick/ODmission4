import time
from selenium import webdriver

def WaitFindElement(browser, maxTime, by, target):
	tmpTime = 0
	res = None
	while tmpTime<=maxTime:
		try:
			res = browser.find_element(by, target)
			tmpTime += (maxTime+1)
		except Exception as e:
			tmpTime += 1
			time.sleep(1)

	return res

def WaitGetURL(browser, maxTime):
	tmpTime = 0
	res = None
	while tmpTime<=maxTime:
		res = browser.current_url
		if res!="about:blank":
			tmpTime += (maxTime+1)
		else:
			tmpTime += 1
			time.sleep(1)

	return res