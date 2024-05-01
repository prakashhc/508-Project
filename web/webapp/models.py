'''from django.contrib.auth.models import AbstractUser

class CustomUser(AbstractUser):
    email = models.EmailField(primary_key=True)
    first_name = models.CharField(max_length=32)
    last_name = models.CharField(max_length=32)
    password = models.CharField(max_length=18)
    

# Your other models go here, like the ones you provided in your initial code

'''
from django.db import models
from django.contrib.auth.models import User

class Building(models.Model):
    id = models.AutoField(primary_key=True)
    address = models.CharField(max_length=100)
    city = models.CharField(max_length=50)
    state = models.CharField(max_length=2)
    zipCode = models.CharField(max_length=5)
    buildingName = models.CharField(max_length=100, blank=True)
    #user = models.OneToOneField(User, on_delete=models.CASCADE, null=True, related_name='building')

    class Meta:
        constraints = [
            models.UniqueConstraint(fields=['address', 'city', 'state', 'zipCode'], name='unique_building')
        ]

    def __str__(self):
        return self.address

#class CustomUser(User):
#    user_building = models.ForeignKey(Building, on_delete=models.SET_NULL, null=True, related_name='users')

'''
class User(models.Model):
    email = models.EmailField(primary_key=True)
    first_name = models.CharField(max_length=32)
    last_name = models.CharField(max_length=32)
    password = models.CharField(max_length=18)

class Allergy(models.Model):
    Allergen = models.CharField(max_length=255, primary_key=True)

class User_Allergies(models.Model):
    Allergen = models.ForeignKey(Allergy, on_delete=models.CASCADE)
    email = models.ForeignKey(User, on_delete=models.CASCADE)
    class Meta:
        unique_together = ('Allergen', 'email')

class Food_Allergies(models.Model):
    Allergen = models.ForeignKey(Allergy, on_delete=models.CASCADE)
    PostID = models.ForeignKey('Post', on_delete=models.CASCADE)
    class Meta:
        unique_together = ('Allergen', 'PostID')

class Courier(models.Model):
    email = models.OneToOneField(User, primary_key=True, on_delete=models.CASCADE)
    buildingID = models.ForeignKey(Building, on_delete=models.CASCADE)

class Post(models.Model):
    postID = models.AutoField(primary_key=True)
    orderCapacity = models.IntegerField()
    datePosted = models.DateTimeField()
    email = models.ForeignKey(User, on_delete=models.CASCADE)
    type = models.CharField(max_length=10, choices=[('Home_Cooked', 'Home_Cooked'), ('Fast_Food', 'Fast_Food')], null=False)

class Restaurant(models.Model):
    restaurantName = models.CharField(max_length=60, primary_key=True)

class Order_Item(models.Model):
    orderID = models.AutoField(primary_key=True)
    postID = models.ForeignKey(Post, on_delete=models.CASCADE)

class Customer(models.Model):
    email = models.ForeignKey(User, on_delete=models.CASCADE, primary_key=True)
    orderID = models.ForeignKey(Order_Item, on_delete=models.CASCADE)

class Order_FoodItem(models.Model):
    orderID = models.ForeignKey(Order_Item, on_delete=models.CASCADE)
    foodID = models.ForeignKey('FoodItem', on_delete=models.CASCADE)
    class Meta:
        unique_together = ('orderID', 'foodID')

class FoodItem(models.Model):
    foodID = models.AutoField(primary_key=True)
    name = models.CharField(max_length=255)
    price = models.DecimalField(max_digits=10, decimal_places=2)
    restaurant = models.ForeignKey(Restaurant, on_delete=models.CASCADE)

class Home_Cooked(models.Model):
    postID = models.OneToOneField(Post, on_delete=models.CASCADE, primary_key=True)

class Fast_Food(models.Model):
    postID = models.OneToOneField(Post, on_delete=models.CASCADE, primary_key=True)
    restaurant = models.ForeignKey(Restaurant, on_delete=models.CASCADE)

    '''
