#!/usr/bin/
# -*- coding: utf-8 -*-

# 爬取文章

import requests, json, math, re, pymysql, sys, getopt
from bs4 import BeautifulSoup
from lxml import etree

reload(sys)   
sys.setdefaultencoding('utf8')

database = {
    'host' : 'localhost',
    'user' : 'root',
    'password' : '8355189',
    'database' : '7reading',
    'port' : 3306,
    'charset' : 'utf8'
}

conn=pymysql.connect(host=database['host'],user=database['user'],passwd=database['password'],db=database['database'],port=database['port'],charset=database['charset'])

class EssayFetcher(object):

  def __init__(self, url, author):
    self.url = url # 待爬取的文章地址
    self.author = author
    self.encoding = 'gbk'

  def start(self):
    page = self.getPage(self.url)
    if page is not None:
      essay = self.parse(page)
      if essay is not None:
        self.saveEssay(essay)
      else:
        print(u'文章不存在\n')

  def getPage(self, url):
    try:
      r = requests.get(url, timeout=10)
      sp = BeautifulSoup(r.content, "html.parser")
      page = etree.HTML(sp.prettify())
      return page
    except Exception as e:
      print('request failed', e)
      pass
      return None

  def parse(self, page):
    essay = {}
    essay['author'] = self.author
    essays = page.xpath("//font[@color='#dc143c']/text()")
    if len(essays) is 0:
      return None
    else:
      essay['title'] = essays[0]
      essay['title'] = essay['title'].replace('\n', '')
      essay['title'] = essay['title'].strip()
      essay['title'] = essay['title'].replace(u'短篇小说 ', '')
      content = ''.join(page.xpath('./body/div/table[5]//p/text()'))
      # content = etree.tostring(page.xpath('./body/div/table[5]//p')[0], encoding='gbk')
      content, num = re.subn(r'\s+\n', '\n\n', content)
      r = re.compile(r'^\s+', re.M)
      content, num = r.subn(u'　　', content)
      content, num = re.subn(r'\n', '\n\n', content)
      try:
        essay['content'] = content.encode(self.encoding).decode(self.encoding).encode('utf-8')
      except Exception as e:
        essay = None
      return essay

  def saveEssay(self, essay):
    try:
      with conn.cursor() as cursor:
        # 先判断文章是否已存在
        sql = "SELECT * FROM essay WHERE title=%s AND author=%s"
        result = cursor.execute(sql, (essay['title'], essay['author']))
        conn.commit()
        if result:
          print(u'文章已存在\n')
        else:
          sql = "INSERT INTO essay (title, author, content) VALUES (%s, %s, %s)"
          cursor.execute(sql, (essay['title'], essay['author'], essay['content']))
          conn.commit()
          print(essay['title'] + u': 文章保存成功\n')
        cursor.close()
    except Exception as e:
      print(e)
      print(essay['title'] + u': 文章保存失败\n')
      pass
    # with open('./' + essay['title'] + '.txt', 'wb') as f:
    #   f.write(essay['content'])
    # print(essay['title'] + u': 保存成功\n')

def getArgs(opts):
  args = {}
  for opt in opts:
    args[opt[0][2:]] = opt[1]
  return args

if __name__ == '__main__':
  opts, args = getopt.getopt(sys.argv[1:], None, ["author=", "bookSerie=", "bookId=", "startId=", "endId="])
  args = getArgs(opts)
  # author = raw_input('please input book author:\n')
  # author = author.decode('gbk').encode('utf-8')
  # bookSerie = raw_input('please input book serie:\n')
  # bookId = input('please input book id:\n')
  # startId = input('please input start id:\n')
  # endId = input('please input end id:\n')
  startId = int(args['startId'])
  endId = int(args['endId'])
  args['author'] = args['author'].decode('gbk').encode('utf-8')
  print(args)
  while(startId <= endId):
    fetcher = EssayFetcher('http://www.kanunu8.com/' + args['bookSerie'] + '/' + args['bookId'] + '/' + str(startId) + '.html', args['author'])
    fetcher.start()
    startId = startId + 1
  conn.close()
    