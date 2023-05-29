function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('ipaddress') then
  span.content = pandoc.Str('xxx.xxx.xxx.xxx')
  local value, position = span.classes:find('ipaddress')
  span.classes:remove(position)
end
return span
end