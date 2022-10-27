from flask import Flask, render_template, url_for, redirect, request, flash
from flask_mysqldb import MySQL

app = Flask(__name__)

#Mysql connection
app.config['MYSQL_HOST']='172.18.0.1'
#app.config['MYSQL_PORT']='3306'
app.config['MYSQL_USER']='root'
app.config['MYSQL_PASSWORD']='Piragenth@3559'
app.config['MYSQL_DB']='python'
app.config['MYSQL_CURSORCLASS']='DictCursor'
mysql = MySQL(app)

@app.route('/')
def hello_world():
    con = mysql.connection.cursor()
    sql = 'select * from users'
    con.execute(sql)
    res = con.fetchall()
    return render_template('index.html',datas=res)

#New user
@app.route('/adduser',methods=['GET','POST'])

def adduser():
    if request.method == 'POST':
        name=request.form['name']
        city=request.form['city']
        contact_no=request.form['contact_no']
        con = mysql.connection.cursor()
        sql = 'insert into users(name,contact_no,city) values(%s,%s,%s)'
        con.execute(sql,[name,contact_no,city])
        mysql.connection.commit()
        con.close()
        flash('user added')
        return redirect(url_for("hello_world"))
    return render_template('adduser.html')

@app.route('/edit-user/<string:id>', methods=['GET','POST'])

def Edit_user(id):
    con=mysql.connection.cursor()
    if request.method == 'POST':
        name = request.form['name']
        city = request.form['city']
        contact_no = request.form['contact_no']
        sql = 'update users set name=%s,contact_no=%s,city=%s where id=%s'
        con.execute(sql,[name,contact_no,city,id])
        mysql.connection.commit()
        con.close()
        flash('user edited')
        return redirect(url_for('hello_world'))
    sql = 'select * from users where id=%s'
    con.execute(sql,[id])
    res=con.fetchone()
    return render_template('edit.html',datas=res)

@app.route('/delete-user/<string:id>',methods=['GET',"POST"])
def delete_user(id):
    con = mysql.connection.cursor()
    sql = 'delete from users where id=%s'
    con.execute(sql,id)
    mysql.connection.commit()
    con.close
    flash('user deleted')
    return redirect(url_for('hello_world'))

@app.route('/home')
def home():
   return '<h1>Iam at Home</h1>'

#app.add_url_rule('/','hello',hello_world)

@app.route('/home/<call>')

def name(call):
    return f'Hello {call}'    


if __name__ == '__main__':
    app.secret_key='piragenth'
    app.run(host='0.0.0.0',debug='True', port='80')
