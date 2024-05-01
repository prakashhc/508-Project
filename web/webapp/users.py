''' DO NOT NEED BECAUSE WE ARE USING auth_user (made by django)

from django.contrib.auth.models import AbstractBaseUser, BaseUserManager
from django.db import models
from django.contrib.auth.hashers import check_password
from django.contrib.auth.hashers import make_password
import bcrypt

def hash_password(password):
    """Hash the provided password using bcrypt"""
    salt = bcrypt.gensalt()
    hashed_password = bcrypt.hashpw(password.encode('utf-8'), salt)
    return hashed_password.decode('utf-8')

def verify_password(password, hashed_password):
    """Verify the provided password against the hashed password"""
    return bcrypt.checkpw(password.encode('utf-8'), hashed_password)


class UserManager(BaseUserManager):
    def create_user(self, username, first_name, last_name, password=None, **extra_fields):
        if not username:
            raise ValueError('Users must have a username')
        email = self.normalize_email(username)
        user = self.model(
            username=username,
            email=email,
            first_name=first_name,
            last_name=last_name,
            **extra_fields
        )
        # Hash the password using bcrypt
        user.set_password(password)
        user.save(using=self._db)
        return user



class User(AbstractBaseUser):
    email = models.EmailField(unique=True, max_length=255, primary_key=True)  # Set email as primary key
    first_name = models.CharField(max_length=255)
    last_name = models.CharField(max_length=255)
    building_id = models.IntegerField(null=True, blank=True)
    objects = UserManager()
    USERNAME_FIELD = 'email'
    REQUIRED_FIELDS = ['first_name', 'last_name']

    def check_password(self, raw_password):
        """Verify the provided password against the hashed password"""
        return verify_password(raw_password, self.password)

    def has_perm(self, perm, obj=None):
        return True

    def has_module_perms(self, app_label):
        return True

    @property
    def is_staff(self):
        return False

    def __str__(self):
        return self.email

    class Meta:
        db_table = 'webapp_user'  # Change the table name here to avoid conflict
