require 'rubygems'
require 'sinatra'
require 'vine_client'
#require 'haml'
#require 'sass'
#require 'coffee-script'

get '/' do

  user=Vine::Client.new('tkm.knzk@gmail.com','vinetodflwf6')

  tag = user.tag('hoge')

  erb :index,
    :locals => {
    #:feed_blog => feed_blog,
    #:feed_tech => feed_tech
  }

end


