package main

import (
	"crypto/tls"
	"database/sql"
	"encoding/json"
	"flag"
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"strconv"
	"strings"
	"time"

	_ "github.com/go-sql-driver/mysql"
	gomail "gopkg.in/mail.v2"

	"github.com/common-nighthawk/go-figure"
	"github.com/fatih/color"
)

var (
	IP         string
	ipflag     string
	emailflag  string
	emailbody  string
	valuesText []string
)

type shodanjson struct {
	Cpes      []string `json:"cpes"`
	Hostnames []string `json:"hostnames"`
	IP        string   `json:"ip"`
	Ports     []int    `json:"ports"`
	Tags      []string `json:"tags"`
	Vulns     []string `json:"vulns"`
}

type databasestruct struct {
	email  string
	ipaddr string
}

func sendmail(emailaddr string, body string) {
	fmt.Println("\n ")
	color.Blue("Sending an email to the user...")

	m := gomail.NewMessage()

	// Set E-Mail sender
	m.SetHeader("From", "sender-email")

	// Set E-Mail receivers
	m.SetHeader("To", emailaddr)

	// Set E-Mail subject
	m.SetHeader("Subject", "Here is your QuickTrack Report")

	// Set E-Mail body. You can set plain text or html with text/html
	m.SetBody("text/html", body)

	// Settings for SMTP server
	d := gomail.NewDialer("smtp.gmail.com", 587, "your-email-here", "your-password-here") //Quick_track1

	// This is only needed when SSL/TLS certificate is not valid on server.
	// In production this should be set to false.
	d.TLSConfig = &tls.Config{InsecureSkipVerify: true}

	// Now send E-Mail
	if err := d.DialAndSend(m); err != nil {
		fmt.Println(err)
	} else {
		fmt.Println("\n ")
		log.Print("Sent an email to:", emailaddr)
	}

}

func banner() {
	fmt.Println("\n ")
	banner := figure.NewColorFigure("QuickTrack", "", "blue", true)
	banner.Print()
	fmt.Println("\n ")
	color.Blue("\t\tA Robust and Stealthy IP Monitoring Tool")
}

func input() (string, string) {
	flag.StringVar(&ipflag, "ip", "", "IP Address To Scan")
	flag.StringVar(&emailflag, "email", "", "Email Address To Use")
	flag.Parse()
	return ipflag, emailflag
}

func shodan(emailid string, targetip string) {
	fmt.Println("\n ")
	color.Blue("Initiating Shodan Scan...")
	fmt.Println("\n ")
	shodanurl := "https://internetdb.shodan.io/" + targetip
	resp, err := http.Get(shodanurl)
	if err != nil {
		log.Fatalln(err)
	}

	body, err := ioutil.ReadAll(resp.Body)
	if err != nil {
		log.Fatalln(err)
	}

	var shodan shodanjson
	err = json.Unmarshal(body, &shodan)
	if err != nil {
		log.Println(err.Error())
	}

	log.Println("Target IP: ", shodan.IP)
	log.Println("CPES Discovered:", shodan.Cpes)
	log.Println("Hostnames Discovered:", shodan.Hostnames)
	log.Println("Ports Identified:", shodan.Ports)
	log.Println("Tags Identified:", shodan.Tags)
	log.Println("Vulnerabilities Discovered:", shodan.Vulns)

	cpes := strings.Join(shodan.Cpes, ", ")
	hostnames := strings.Join(shodan.Hostnames, ", ")
	for i := range shodan.Ports {
		number := shodan.Ports[i]
		porttext := strconv.Itoa(number)
		valuesText = append(valuesText, porttext)
	}
	ports := strings.Join(valuesText, ", ")
	tags := strings.Join(shodan.Tags, ", ")
	Vulnerabilities := strings.Join(shodan.Vulns, ", ")
	currenttime := time.Now()

	emailbody = `
	            <div style="background-color: rgb(238, 238, 238);">
	            <img src="https://i.postimg.cc/rwHgysX9/Untitled-design-5.png" style="display: block; margin-left: auto; margin-right: auto;">
	            </div>
                <div style="background-color: rgb(247, 247, 247); padding-top: 30px; padding-left: 40px;">
	            Dear User, <br><br>
                
                QuickTrack has successfully finished with the scan at <b>` + currenttime.Format("2006-01-02 15:04:05") + `</b> and here is your weekly report for the target IP address (<b>` + targetip + `</b>):
                <br><br>
                <b>Hostnames Discovered: </b>` + hostnames + `
                <br>
                <b>Ports Identified: </b>` + ports + `
                <br>
                <b>Services Running On These Ports: </b>` + cpes + `
                <br>
                <b>Tags Allocated: </b>` + tags + `
                <br>
                <b>Vulnerabilities Discovered: </b>` + Vulnerabilities + `
                <br><br>
				<p> 
				<div style="float:left;">Regards,<br>QuickTrack Team<br><br></div>
                <div style="float:right;"><img src="https://i.ibb.co/gvrwsFt/image.png" alt="image" border="0"></div>
                <div style="clear: left;"/>
                </p>
				</div>
				<div style="background-color: rgb(238, 238, 238); padding-top: 30px; padding-bottom: 30px;">
				<center><b>Made With ❤️ && 💻 && ☕ At QuickTrack</b></center>
				</div>
	`
	sendmail(emailid, emailbody)
}

func fullscan() {
	db, err := sql.Open("mysql", "username:password@tcp(ipaddress:port)/dbname")
	if err != nil {
		panic(err)
	}
	db.SetConnMaxLifetime(time.Minute * 3)
	db.SetMaxOpenConns(10)
	db.SetMaxIdleConns(10)

	err = db.Ping()
	if err != nil {
		fmt.Println(err.Error())
	}

	rows, err := db.Query("SELECT Email, IP FROM quicktrack")
	if err != nil {
		log.Println(err)
	}
	defer rows.Close()

	for rows.Next() {
		var entry databasestruct
		if err := rows.Scan(&entry.email, &entry.ipaddr); err != nil {
			fmt.Println(err)
		}
		shodan(entry.email, entry.ipaddr)
	}
	if err = rows.Err(); err != nil {
		log.Println(err)
	}

}

func dbinsert(email string, IPaddr string) {
	fmt.Println("\n ")
	color.Blue("Adding entry to database...")
	db, err := sql.Open("mysql", "username:password@tcp(ipaddress:port)/dbname")
	if err != nil {
		panic(err)
	}
	db.SetConnMaxLifetime(time.Minute * 3)
	db.SetMaxOpenConns(10)
	db.SetMaxIdleConns(10)

	err = db.Ping()
	if err != nil {
		fmt.Println(err.Error())
	}
	sqlquery := "INSERT INTO quicktrack (Email, IP) VALUES ('" + email + "', '" + IPaddr + "')"
	rows, err := db.Query(sqlquery)
	if err != nil {
		log.Println(err)
	} else {
		log.Println("Saved to DB...")
	}
	defer rows.Close()

}

func main() {
	banner()
	IP, mail := input()
	if IP == "" {
		log.Println("Error! Make sure you have provided -ip flag along with a proper IP address")
	} else if IP == "full" {
		fullscan()
	} else if mail != "" {
		dbinsert(mail, IP)
		shodan(mail, IP)
	}
}
