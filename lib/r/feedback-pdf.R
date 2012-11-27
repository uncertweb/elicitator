# Modified from SHELF 2.0, 11/01/2011
# Main variables 
# Type of probability distribution: Distribution
# Lower bound: Lo 
# Upper bound: Up 
# Mean (mu) and variance (sigma): parameters
# Sum of square error: lsq 

################################# Probability density function feedback ######################################################################

args <- commandArgs(TRUE)
Distribution = args[1]
Lo = as.numeric(args[2])
Up = as.numeric(args[3])
temp_parameters = unlist(strsplit(args[4], ","))

n<-length(temp_parameters)
parameters<-array(0,n)

for(i in 1:n) {
   parameters[i]<- as.numeric(temp_parameters[i])
}


x<-seq(from=Lo,to=Up,length=102)[2:101]

if (Distribution == "beta"){
 output<-1/(Up-Lo)*dbeta((x-Lo)/(Up-Lo),parameters[1],parameters[2])
 
 #plot(x,f.beta,ylim=c(0,max(f.beta)),type="l",xlab="",ylab="")
}
if (Distribution == "gamma"){
 output<-dgamma(x,parameters[1],parameters[2])
 #plot(x,f.gamma,ylim=c(0,max(f.gamma)),type="l",xlab="",ylab="")
}

if (Distribution == "normal"){
 output<-dnorm(x,parameters[1],parameters[2])
 #plot(x,f.normal,ylim=c(0,max(f.normal)),type="l",xlab="",ylab="")
}
if (Distribution == "log-normal"){
 output<-dlnorm(x,parameters[1],parameters[2])
 #plot(x,f.lognormal,ylim=c(0,max(f.lognormal)),type="l",xlab="",ylab="")
}
if (Distribution == "log-student-t"){
 output<-dt((log(x)-parameters[1])/parameters[2],parameters[3])/(parameters[2]*x)
 #plot(x,f.logt,ylim=c(0,max(f.logt)),type="l",xlab="",ylab="")
}
if (Distribution == "student-t"){
 output<-dt((x-parameters[1])/parameters[2],parameters[3])/(parameters[2])
 #plot(x,f.t,ylim=c(0,max(f.t)),type="l",xlab="",ylab="")
}

write(output, "")
