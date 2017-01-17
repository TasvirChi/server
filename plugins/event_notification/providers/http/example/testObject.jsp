<%@ page import = "java.util.Map.Entry" %>
<%@ page import = "java.util.HashMap" %>
<%@ page import = "org.w3c.dom.Element" %>
<%@ page import = "com.borhan.client.utils.ParseUtils" %>
<%@ page import = "com.borhan.client.utils.XmlUtils" %>
<%@ page import = "com.borhan.client.types.BorhanHttpNotification" %>
<%
String xmlData = request.getParameter("data");
Element xmlElement = XmlUtils.parseXml(xmlData);
BorhanHttpNotification httpNotification = ParseUtils.parseObject(BorhanHttpNotification.class, xmlElement);
HashMap<String, String> params = httpNotification.toParams();
for (Entry<String, String> itr : params.entrySet()) {
	out.println(itr.getKey() + " => " + itr.getValue());
}
%>