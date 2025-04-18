import React from "react";
import "./LandingPage.css";
import logo from "../../Assets/logo.png";
import { Link } from "react-router-dom";

const Footer = () => {
  return (
    <footer className="footer">
      <div className="section__container footer__container">
        <div className="footer__col">
          <div className="footer__logo">
            <a href="#">
              <img src={logo} alt="logo" />
              Power
            </a>
          </div>
          <p>
            Take the first step towards a healthier, stronger you with our
            unbeatable pricing plans. Let's sweat, achieve, and conquer
            together!
          </p>
          <div className="footer__socials">
            <a href="#">
              <i className="ri-facebook-fill"></i>
            </a>

            <a href="#">
              <i className="ri-instagram-fill"></i>
            </a>
            <a href="#">
              <i className="ri-twitter-fill"></i>
            </a>
          </div>
        </div>
        <div class="footer__col">
          <h4>Company</h4>
          <div class="footer__links">
            <Link to={"business-page"}>Business</Link>
            <Link to={"/franchise-page"}>Franchise</Link>
            <Link to={"/partnership-page"}>Partnership</Link>
            <Link to={"/netwok-page"}>Network</Link>
          </div>
        </div>
        <div class="footer__col">
          <h4>About Us</h4>
          <div class="footer__links">
            <Link to={"/blog-page"}>Blogs</Link>
            <Link to={"/security-page"}>Security</Link>
            <Link to={"career-page"}>Careers</Link>
          </div>
        </div>
        <div class="footer__col">
          <h4>Contact</h4>
          <div class="footer__links">
            <Link to={"/contact-us"}>Contact Us</Link>
            <Link to={"/privacy-policy"}>Privacy Policy</Link>
            <Link to={"/terms-conditions"}>Terms & Conditions</Link>
            <Link to={"/bmi-calculator"}>BMI Calculator</Link>
          </div>
        </div>
      </div>
      <div class="footer__bar">
        Copyright © 2025 Web Design Mastery. All rights reserved.
      </div>
    </footer>
  );
};

export default Footer;
