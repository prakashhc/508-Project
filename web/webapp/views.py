from django.shortcuts import render, redirect
from django.contrib.auth.models import User
from django.contrib.auth import login, logout, authenticate
from .models import Building
from django.db import IntegrityError
from django.contrib import messages
import re

# Create your views here.

def index(request):
    if request.user.is_authenticated: #these if/else statements only allow the user to view the index page if they've signed in
        return render(request, "index.html")
    else:
        return redirect('/signin')

def deliver(request):
    if request.user.is_authenticated: #these if/else statements only allow the user to view the index page if they've signed in
        return render(request, "deliver.html")
    else:
        return redirect('/signin')

def order(request):
    if request.user.is_authenticated: #these if/else statements only allow the user to view the index page if they've signed in
        return render(request, "order.html")
    else:
        return redirect('/signin')

def profile(request):
    if request.user.is_authenticated: #these if/else statements only allow the user to view the index page if they've signed in
        return render(request, "profile.html")
    else:
        return redirect('/signin')

def signin(request):
    if request.user.is_authenticated:
        return render(request, "index.html")
    else:
        if request.method=="POST":
            email=request.POST['email']
            password=request.POST['password']
            user=authenticate(username=email,password=password)
            if user is not None:
                login(request,user)
                return redirect('/index') #takes the user to the home page once they've successfully logged in
            else:
                return redirect('/signin') 
        else:
            return render(request,"login.html")

def signout(request):
    logout(request)
    return redirect('/signin')

def signup(request):
    if request.user.is_authenticated:
        return render(request, "index.html")
    else:
        if request.method == "POST":
            first_name = request.POST['firstName']
            last_name = request.POST['lastName']
            username = request.POST['email']  # Use email as username
            password = request.POST['password']
            confpassword = request.POST['confirmpassword']

            # Get building_id from the form
            building_id = request.POST.get('building')

            # Check if email contains '@'
            if '@' not in username:
                messages.error(request, "Email address must contain '@' symbol.")
            # Check if required fields are empty
            elif not first_name or not last_name or not username or not password or not confpassword or not building_id:
                messages.error(request, "All fields are required.")
            # Check if passwords match
            elif password != confpassword:
                messages.error(request, "Passwords do not match.")
            else:
                try:
                    # Create a User instance
                    user = User.objects.create_user(username=username, password=password, first_name=first_name, last_name=last_name)
                    # Set the building for the user
                    building = Building.objects.get(id=building_id)
                    user.user_building = building
                    user.save()
                    login(request,user)
                    return redirect('/index')

                except Building.DoesNotExist:
                    messages.error(request, "Invalid building selection.")

        # Get all buildings for the dropdown
        buildings = Building.objects.all()
        return render(request, "signup.html", {'buildings': buildings})
