""" 
AMQP consumer to fetch url

@author Osman Yuksel < yuxel |ET| sonsuzdongu |DOT| com >

"""

from amqplib import client_0_8 as amqp
import simplejson as json 
import os.path, time, httplib

class eksigator():
    def __init__(self):
        self.max_time = 1800 # half an hour
        self.base_url = "www.eksisozluk.com"
        self.http_conn = httplib.HTTPConnection(self.base_url)
    def check_file_to_fetch(self, file):
        exists=os.path.exists(file)
        
        if exists :
            now = time.time()
            filemtime=os.path.getmtime(file)
            time_diff = int(now - filemtime)
            if time_diff > self.max_time :
                #print "true time elpase"
                return True
            else : 
                #print "false, time not elpase"
                return False
        else :
            #print "true, not exists"
            return True

    def fetch_content(self, url):
        #conn = httplib.HTTPConnection(self.base_url)
        #conn.request("GET", "/amqp/test.php")
        self.http_conn.request("GET", url)
        r1 = self.http_conn.getresponse()
        data = r1.read()
        #self.http_conn.close()
        return data

    def write_content_to_file(self, content, file):
        f = open(file, 'w')
        f.write(content)
        f.close() 

    def fetch(self, url, file) :
        if self.check_file_to_fetch(file) :
            print "---"
            print "fetching " + url
            content = self.fetch_content(url)
            print "saving content to  " + file
            time.sleep(5)
            self.write_content_to_file(content, file)
            print "done ..."

class rabbitMQEksigatorQueue:
    # config parameters
    def __init__(self):
        self.rabbitMQConf = {"hostPort" : "localhost:5672",
                             "user" : "guest",
                             "pass" : "guest",
                             "virtualHost" : "/"
        }

        self.queueConf = {"name" : "eksigators",
                          "exchange" : "bulletin"
        }


    def setFetcher(self, fetcher) :
        self.fetcher = fetcher

    def listen(self):
        try:
            conn = amqp.Connection(self.rabbitMQConf['hostPort'],
                                   self.rabbitMQConf['user'],
                                   self.rabbitMQConf['pass'],
                                   virtualHost = self.rabbitMQConf['virtualHost'],
                                   insist = False)
            chan = conn.channel()

            chan.queue_declare( self.queueConf['name'],
                                durable=True,  
                                exclusive=False,
                                auto_delete=False) 

            chan.exchange_declare(self.queueConf['exchange'],
                                  type="direct", 
                                  durable=True, 
                                  auto_delete=False,) 

            # bind queue with exchange
            chan.queue_bind(self.queueConf['name'], self.queueConf['exchange'])

            # set consumers callback method
            chan.basic_consume(self.queueConf['name'], 
                               no_ack=True,
                               callback=self.fetchUrl) 
            # start listening
            while True:
                chan.wait()

            chan.close()
            conn.close()

        except Exception, error :
            print "Something went wrong : ",error

    def fetchUrl(self,msg):
        message = json.loads(msg.body)
        url = message['url']
        filename = message['hash']
        self.fetcher.fetch(url, filename)

eksigatorQueue = rabbitMQEksigatorQueue()
eksigator = eksigator()
eksigatorQueue.setFetcher(eksigator)
eksigatorQueue.listen()
